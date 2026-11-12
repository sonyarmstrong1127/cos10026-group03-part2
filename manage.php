<?php
// manage.php – HR manager queries on the eoi table (login required).
// OWNER: Phan Quang Huy
//
// GET  (search form)  -> list EOIs: all / by job reference / by applicant name, with a sort field
// POST action=status  -> change the status of one EOI
// POST action=delete  -> delete all EOIs with a job reference

// ---------------------------------------------------------------
// 1. Only logged-in managers may use this page
// ---------------------------------------------------------------
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
require_once "settings.php";

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
function get_value($source, $name) {
    return (isset($source[$name]) && is_string($source[$name])) ? clean_input($source[$name]) : "";
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}
mysqli_set_charset($conn, "utf8mb4");

$allowed_status = ["New", "Current", "Final"];

// ---------------------------------------------------------------
// 2. Actions that change data (POST), then redirect back with a message
//    (Post/Redirect/Get: refreshing the page will not repeat the action)
// ---------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action  = get_value($_POST, "action");
    $message = "";

    if ($action === "status") {
        $eoi_number = (int) get_value($_POST, "eoi_number");
        $status     = get_value($_POST, "status");
        if ($eoi_number > 0 && in_array($status, $allowed_status, true)) {
            $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
            mysqli_stmt_bind_param($stmt, "si", $status, $eoi_number);
            mysqli_stmt_execute($stmt);
            $message = mysqli_stmt_affected_rows($stmt) > 0
                ? "EOI $eoi_number status changed to $status."
                : "EOI $eoi_number was not changed.";
            mysqli_stmt_close($stmt);
        } else {
            $message = "Please choose a valid status.";
        }
    } elseif ($action === "delete") {
        $jobref  = strtoupper(get_value($_POST, "delete_jobref"));
        $confirm = isset($_POST["confirm"]);
        if (!preg_match("/^[A-Z0-9]{5}$/", $jobref)) {
            $message = "Please enter a 5-character job reference to delete.";
        } elseif (!$confirm) {
            $message = "Please tick the confirm box before deleting.";
        } else {
            $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
            mysqli_stmt_bind_param($stmt, "s", $jobref);
            mysqli_stmt_execute($stmt);
            $count = mysqli_stmt_affected_rows($stmt);
            $message = "Deleted $count EOI(s) for job $jobref.";
            mysqli_stmt_close($stmt);
        }
    }

    $_SESSION["manage_message"] = $message;
    $back = isset($_POST["return"]) && is_string($_POST["return"]) ? $_POST["return"] : "";
    header("Location: manage.php" . ($back !== "" ? "?" . filter_query($back) : ""));
    exit();
}

// Keep the current filters after a POST (only known keys are kept)
function filter_query($query) {
    parse_str($query, $params);
    $keep = array_intersect_key($params, array_flip(["jobref", "firstname", "lastname", "sort", "order"]));
    return http_build_query($keep);
}

// ---------------------------------------------------------------
// 3. Read the search options (GET)
// ---------------------------------------------------------------
$f_jobref    = strtoupper(get_value($_GET, "jobref"));
$f_firstname = get_value($_GET, "firstname");
$f_lastname  = get_value($_GET, "lastname");

// Sort field: only column names from this list can reach the SQL
$sort_fields = [
    "EOInumber"  => "EOI number",
    "job_ref"    => "Job reference",
    "first_name" => "First name",
    "last_name"  => "Last name",
    "status"     => "Status",
    "created_at" => "Date applied",
];
$sort  = get_value($_GET, "sort");
if (!array_key_exists($sort, $sort_fields)) {
    $sort = "EOInumber";
}
$order = get_value($_GET, "order") === "desc" ? "DESC" : "ASC";

// ---------------------------------------------------------------
// 4. Build the query: list all, or filter by job reference and/or name
// ---------------------------------------------------------------
$where  = [];
$types  = "";
$values = [];
if ($f_jobref !== "") {
    $where[] = "job_ref = ?";
    $types  .= "s";
    $values[] = $f_jobref;
}
if ($f_firstname !== "") {
    $where[] = "first_name LIKE ?";
    $types  .= "s";
    $values[] = "%" . $f_firstname . "%";
}
if ($f_lastname !== "") {
    $where[] = "last_name LIKE ?";
    $types  .= "s";
    $values[] = "%" . $f_lastname . "%";
}

$sql = "SELECT * FROM eoi";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY $sort $order";   // safe: $sort and $order come from fixed lists above

$stmt = mysqli_prepare($conn, $sql);
$rows = [];
if ($stmt) {
    if ($types !== "") {
        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

$message = isset($_SESSION["manage_message"]) ? $_SESSION["manage_message"] : "";
unset($_SESSION["manage_message"]);
$return_query = http_build_query(["jobref" => $f_jobref, "firstname" => $f_firstname,
                                  "lastname" => $f_lastname, "sort" => $sort, "order" => strtolower($order)]);

// ---------------------------------------------------------------
// 5. Page output
// ---------------------------------------------------------------
$page_title       = "Manage applications";
$page_description = "HR manager page for Harvest Power job applications.";
$page_keywords    = "Harvest Power, manage, EOI";
$page_author      = "Phan Quang Huy, COS10026 Group 03";
$current_page     = "manage";
$page_style       = <<<CSS
    .manage-table { border-collapse: collapse; width: 100%; font-size: 0.9em; }
    .manage-table th, .manage-table td { border: 1px solid #bdbdbd; padding: 0.4em; text-align: left; vertical-align: top; }
    .manage-table th { background-color: #1b5e20; color: #ffffff; }
    .manage-forms { display: flex; flex-wrap: wrap; gap: 1.5em; }
    .manage-forms fieldset { flex: 1 1 18em; }
    .manage-message { background-color: #e8f5e9; border-left: 4px solid #1b5e20; padding: 0.6em; }
    .table-wrap { overflow-x: auto; }
    .visually-hidden { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
CSS;
include "header.inc";
?>
  <main id="main-content">
    <h1>Manage applications</h1>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION["username"]); ?>. <a href="logout.php">Log out</a></p>

<?php if ($message !== ""): ?>
    <p class="manage-message"><?php echo $message; ?></p>
<?php endif; ?>

    <div class="manage-forms">
      <form method="get" action="manage.php">
        <fieldset>
          <legend>Find EOIs</legend>
          <p>Leave every box empty to list all EOIs.</p>
          <p><label for="jobref">Job reference</label><br>
             <input type="text" id="jobref" name="jobref" value="<?php echo $f_jobref; ?>"></p>
          <p><label for="firstname">Applicant first name</label><br>
             <input type="text" id="firstname" name="firstname" value="<?php echo $f_firstname; ?>"></p>
          <p><label for="lastname">Applicant last name</label><br>
             <input type="text" id="lastname" name="lastname" value="<?php echo $f_lastname; ?>"></p>
          <p><label for="sort">Sort by</label><br>
             <select id="sort" name="sort">
<?php foreach ($sort_fields as $field => $label): ?>
               <option value="<?php echo $field; ?>"<?php if ($field === $sort) { echo " selected"; } ?>><?php echo $label; ?></option>
<?php endforeach; ?>
             </select>
             <select id="order" name="order" aria-label="Sort order">
               <option value="asc"<?php if ($order === "ASC") { echo " selected"; } ?>>Ascending</option>
               <option value="desc"<?php if ($order === "DESC") { echo " selected"; } ?>>Descending</option>
             </select></p>
          <p><input type="submit" value="Search"> <a href="manage.php">Show all</a></p>
        </fieldset>
      </form>

      <form method="post" action="manage.php">
        <fieldset>
          <legend>Delete EOIs for a job</legend>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="return" value="<?php echo htmlspecialchars($return_query); ?>">
          <p><label for="delete_jobref">Job reference</label><br>
             <input type="text" id="delete_jobref" name="delete_jobref"></p>
          <p><input type="checkbox" id="confirm" name="confirm" value="yes">
             <label for="confirm">I understand this deletes every EOI for this job</label></p>
          <p><input type="submit" value="Delete EOIs"></p>
        </fieldset>
      </form>
    </div>

    <h2>Results (<?php echo count($rows); ?>)</h2>
<?php if (count($rows) === 0): ?>
    <p>No EOIs found.</p>
<?php else: ?>
    <div class="table-wrap">
    <table class="manage-table">
      <thead>
        <tr>
          <th scope="col">EOI</th><th scope="col">Job</th><th scope="col">Name</th><th scope="col">Date of birth</th>
          <th scope="col">Contact</th><th scope="col">Address</th><th scope="col">Skills</th>
          <th scope="col">Applied</th><th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($rows as $r):
        $skills = [];
        foreach (["html" => "HTML", "css" => "CSS", "solar" => "Solar", "data" => "Data", "other" => "Other"] as $k => $label) {
            if ($r["skill_" . $k]) { $skills[] = $label; }
        }
?>
        <tr>
          <td><?php echo (int) $r["EOInumber"]; ?></td>
          <td><?php echo htmlspecialchars($r["job_ref"]); ?></td>
          <td><?php echo htmlspecialchars($r["first_name"] . " " . $r["last_name"]); ?><br><?php echo htmlspecialchars($r["gender"]); ?></td>
          <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($r["dob"]))); ?></td>
          <td><?php echo htmlspecialchars($r["email"]); ?><br><?php echo htmlspecialchars($r["phone"]); ?></td>
          <td><?php echo htmlspecialchars($r["street"] . ", " . $r["suburb"] . " " . $r["state"] . " " . $r["postcode"]); ?></td>
          <td><?php echo htmlspecialchars(implode(", ", $skills)); ?>
              <?php if (!empty($r["other_skills"])) { echo "<br>" . htmlspecialchars($r["other_skills"]); } ?></td>
          <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($r["created_at"]))); ?></td>
          <td>
            <form method="post" action="manage.php">
              <input type="hidden" name="action" value="status">
              <input type="hidden" name="eoi_number" value="<?php echo (int) $r["EOInumber"]; ?>">
              <input type="hidden" name="return" value="<?php echo htmlspecialchars($return_query); ?>">
              <label for="status-<?php echo (int) $r["EOInumber"]; ?>" class="visually-hidden">Status of EOI <?php echo (int) $r["EOInumber"]; ?></label>
              <select id="status-<?php echo (int) $r["EOInumber"]; ?>" name="status">
<?php foreach ($allowed_status as $s): ?>
                <option value="<?php echo $s; ?>"<?php if ($s === $r["status"]) { echo " selected"; } ?>><?php echo $s; ?></option>
<?php endforeach; ?>
              </select>
              <input type="submit" value="Update">
            </form>
          </td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
    </div>
<?php endif; ?>
  </main>
<?php include "footer.inc"; ?>
