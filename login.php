<?php
// login.php – HR manager login that protects manage.php.
// OWNER: ___
session_start();
require_once "settings.php";

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
