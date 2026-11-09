<?php
// process_eoi.php – receives the Apply form, checks every field on the server
// and stores the Expression of Interest (EOI) in the eoi table.
// OWNER: Phan Quang Huy

// ---------------------------------------------------------------
// 1. Block direct URL access: only accept a POST from apply.php
// ---------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

require_once "settings.php";

// ---------------------------------------------------------------
// 2. Sanitise: remove spaces, backslashes and HTML special characters
// ---------------------------------------------------------------
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Read one text field from the form (empty string if it was not sent)
function get_field($name) {
    return (isset($_POST[$name]) && is_string($_POST[$name])) ? clean_input($_POST[$name]) : "";
}

$jobref      = strtoupper(get_field("jobref"));
$firstname   = get_field("firstname");
$lastname    = get_field("lastname");
$dob         = get_field("dob");
$gender      = get_field("gender");
$street      = get_field("street");
$suburb      = get_field("suburb");
$state       = get_field("state");
$postcode    = get_field("postcode");
$email       = get_field("email");
$phone       = get_field("phone");
$otherskills = get_field("otherskills");
$skills      = (isset($_POST["skills"]) && is_array($_POST["skills"])) ? $_POST["skills"] : [];

// ---------------------------------------------------------------
// 3. Validate every field (the HTML form has no validation any more)
// ---------------------------------------------------------------
$errors = [];

if (!preg_match("/^[A-Z0-9]{5}$/", $jobref)) {
    $errors[] = "Job reference number must be exactly 5 letters or digits.";
}
if (!preg_match("/^[A-Za-z]{1,20}$/", $firstname)) {
    $errors[] = "First name must be 1 to 20 letters.";
}
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastname)) {
    $errors[] = "Last name must be 1 to 20 letters.";
}

// Date of birth: dd/mm/yyyy, a real date, applicant aged 15 to 80
$dob_sql = "";
if (!preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $dob, $parts)) {
    $errors[] = "Date of birth must be in the format dd/mm/yyyy.";
} elseif (!checkdate((int)$parts[2], (int)$parts[1], (int)$parts[3])) {
    $errors[] = "Date of birth is not a real date.";
} else {
    $dob_sql = $parts[3] . "-" . $parts[2] . "-" . $parts[1];   // yyyy-mm-dd for MySQL
    $age = (new DateTime($dob_sql))->diff(new DateTime("today"))->y;
    if ($age < 15 || $age > 80) {
        $errors[] = "Applicants must be between 15 and 80 years old.";
    }
}

if (!in_array($gender, ["female", "male", "other"], true)) {
    $errors[] = "Please select a gender.";
}
if ($street === "" || strlen($street) > 40) {
    $errors[] = "Street address is required (maximum 40 characters).";
}
if ($suburb === "" || strlen($suburb) > 40) {
    $errors[] = "Suburb/town is required (maximum 40 characters).";
}

// State, and the postcode must start with a digit that belongs to that state
$state_first_digits = [
    "VIC" => ["3", "8"], "NSW" => ["1", "2"], "QLD" => ["4", "9"], "NT" => ["0"],
    "WA"  => ["6"],      "SA"  => ["5"],      "TAS" => ["7"],      "ACT" => ["0"],
];
if (!array_key_exists($state, $state_first_digits)) {
    $errors[] = "Please select a state.";
}
if (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (array_key_exists($state, $state_first_digits)
          && !in_array($postcode[0], $state_first_digits[$state], true)) {
    $errors[] = "Postcode $postcode does not match the state $state.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8 to 12 digits or spaces.";
}

// Skills: at least one, only known values
$allowed_skills = ["html", "css", "solar", "data", "other"];
$skills = array_values(array_intersect($skills, $allowed_skills));
if (count($skills) === 0) {
    $errors[] = "Please select at least one skill.";
}
if (in_array("other", $skills, true) && $otherskills === "") {
    $errors[] = "Please describe your other skills, because you ticked \"Other skills\".";
}

// ---------------------------------------------------------------
// 4. Connect to the database
// ---------------------------------------------------------------
mysqli_report(MYSQLI_REPORT_OFF);   // we check the results ourselves
$conn = null;
if (count($errors) === 0) {
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        $errors[] = "Sorry, we cannot save your application right now. Please try again later.";
    } else {
        mysqli_set_charset($conn, "utf8mb4");
    }
}

// The job reference must be one of the jobs in the jobs table
if ($conn && count($errors) === 0) {
    $stmt = mysqli_prepare($conn, "SELECT job_ref FROM jobs WHERE job_ref = ?");
    mysqli_stmt_bind_param($stmt, "s", $jobref);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        $errors[] = "Job reference $jobref does not match any of our current jobs.";
    }
    mysqli_stmt_close($stmt);
}

// ---------------------------------------------------------------
// 5. Create the eoi table if it does not exist yet
// 6. Insert the record with a prepared statement (status = 'New')
// ---------------------------------------------------------------
$eoi_number = 0;
if ($conn && count($errors) === 0) {
    $create = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber    INT UNSIGNED NOT NULL AUTO_INCREMENT,
        job_ref      CHAR(5)      NOT NULL,
        first_name   VARCHAR(20)  NOT NULL,
        last_name    VARCHAR(20)  NOT NULL,
        dob          DATE         NOT NULL,
        gender       ENUM('female','male','other') NOT NULL,
        street       VARCHAR(40)  NOT NULL,
        suburb       VARCHAR(40)  NOT NULL,
        state        ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
        postcode     CHAR(4)      NOT NULL,
        email        VARCHAR(100) NOT NULL,
        phone        VARCHAR(12)  NOT NULL,
        skill_html   TINYINT(1)   NOT NULL DEFAULT 0,
        skill_css    TINYINT(1)   NOT NULL DEFAULT 0,
        skill_solar  TINYINT(1)   NOT NULL DEFAULT 0,
        skill_data   TINYINT(1)   NOT NULL DEFAULT 0,
        skill_other  TINYINT(1)   NOT NULL DEFAULT 0,
        other_skills TEXT         NULL,
        status       ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
        created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (EOInumber)
    ) ENGINE=InnoDB";
    mysqli_query($conn, $create);

    // one 0/1 value for each skill checkbox
    $s_html  = in_array("html",  $skills, true) ? 1 : 0;
    $s_css   = in_array("css",   $skills, true) ? 1 : 0;
    $s_solar = in_array("solar", $skills, true) ? 1 : 0;
    $s_data  = in_array("data",  $skills, true) ? 1 : 0;
    $s_other = in_array("other", $skills, true) ? 1 : 0;
    $other_text = $s_other ? $otherskills : null;

    $sql = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street, suburb, state,
                             postcode, email, phone, skill_html, skill_css, skill_solar, skill_data,
                             skill_other, other_skills)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssiiiiis",
        $jobref, $firstname, $lastname, $dob_sql, $gender, $street, $suburb, $state,
        $postcode, $email, $phone, $s_html, $s_css, $s_solar, $s_data, $s_other, $other_text);

    if (mysqli_stmt_execute($stmt)) {
        $eoi_number = mysqli_insert_id($conn);   // the new EOInumber
    } else {
        $errors[] = "Sorry, your application could not be saved. Please try again.";
    }
    mysqli_stmt_close($stmt);
}
if ($conn) {
    mysqli_close($conn);
}

// ---------------------------------------------------------------
// 7. Show the result page: errors, or the confirmation with the EOInumber
//    (all values were already escaped by clean_input, so they are safe to print)
// ---------------------------------------------------------------
$page_title       = count($errors) > 0 ? "Please check your application" : "Application received";
$page_description = "Result of your job application at Harvest Power.";
$page_keywords    = "Harvest Power, application, confirmation";
$page_author      = "Phan Quang Huy, COS10026 Group 03";
$current_page     = "apply";
include "header.inc";
?>
  <main id="main-content">
<?php if (count($errors) > 0): ?>
    <h1>Please check your application</h1>
    <p>We could not accept your application because of the following problems:</p>
    <ul>
<?php foreach ($errors as $error): ?>
      <li><?php echo $error; ?></li>
<?php endforeach; ?>
    </ul>
    <p><a href="apply.php">Go back to the application form</a></p>
<?php else: ?>
    <h1>Application received</h1>
    <p>Thank you, <?php echo $firstname; ?>. Your application for job <?php echo $jobref; ?> has been received.</p>
    <p>Your Expression of Interest number is <strong><?php echo $eoi_number; ?></strong>. Please keep it for your records.</p>
    <p><a href="index.php">Back to the home page</a></p>
<?php endif; ?>
  </main>
<?php include "footer.inc"; ?>
