<?php
// login.php – HR manager login that protects manage.php.
// OWNER: ___
session_start();
require_once "settings.php";


// login.php – HR manager login that protects manage.php.
// OWNER: Hai

session_start();
require_once "settings.php";

$error_message = "";

// Connect to database
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    $error_message = "Unable to log in at this time.";
} else {

    mysqli_set_charset($conn, "utf8mb4");

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";

        // Find the user in the database
        $sql = "SELECT password_hash FROM users WHERE username = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);

            mysqli_stmt_bind_result($stmt, $password_hash);

            // Verify username and password
            if (mysqli_stmt_fetch($stmt) &&
                password_verify($password, $password_hash)) {

                session_regenerate_id(true);

                $_SESSION["username"] = $username;

                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                header("Location: manage.php");
                exit();

            } else {
                $error_message = "Invalid username or password.";
            }

            mysqli_stmt_close($stmt);

        } else {
            $error_message = "Unable to log in at this time.";
        }
    }

    mysqli_close($conn);
}

$page_title = "Manager login";
$page_description = "Login for Harvest Power HR managers.";
$page_keywords = "Harvest Power, login, manager";
$page_author = "COS10026 Group 03";
$current_page = "manage";

include "header.inc";
?>

<main id="main-content">

    <h1>Manager login</h1>

    <?php if ($error_message !== ""): ?>
        <p><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <p>
            <label for="username">Username</label>
            <input type="text" id="username"
                   name="username" required>
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password"
                   name="password" required>
        </p>

        <p>
            <input type="submit" value="Log in">
        </p>
    </form>

</main>

<?php include "footer.inc"; ?>


/*
  TODO (Part 2 brief, requirement 6 – Authentication):
  - On POST, look up the username in the users table with a prepared statement.
  - Check the password with password_verify() (passwords are stored hashed,
    never as plain text). The marker account is admin / admin.
  - On success: session_regenerate_id(true); $_SESSION["username"] = ...; redirect to manage.php.
  - On failure: show a generic "Invalid username or password" message.
  - Extra for HD: limit failed attempts (e.g. lock out for a few minutes after 3 tries).
*/

$page_title = "Manager login";
$page_description = "Login for Harvest Power HR managers.";
$page_keywords = "Harvest Power, login, manager";
$page_author = "COS10026 Group 03";
$current_page = "manage";
include "header.inc";
?>
  <main id="main-content">
    <h1>Manager login</h1>
    <form method="post" action="login.php">
      <p>
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
      </p>
      <p>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
      </p>
      <p><input type="submit" value="Log in"></p>
    </form>
  </main>
<?php include "footer.inc"; ?>
