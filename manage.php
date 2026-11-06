<?php
// manage.php – HR manager queries on the eoi table (login required).
// OWNER: ___
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
require_once "settings.php";

/*
  TODO (Part 2 brief, requirement 6) – give the manager options to:
  1. List all EOIs.
  2. List EOIs for a job reference.
  3. List EOIs by applicant first name, last name, or both.
  4. Delete all EOIs with a given job reference.
  5. Change the status of an EOI (New / Current / Final).
  6. Choose a sort field for the results (only allow known column names!).
  Use prepared statements for every query that includes user input,
  and htmlspecialchars() when printing values.
*/

$page_title = "Manage applications";
$page_description = "HR manager page for Harvest Power job applications.";
$page_keywords = "Harvest Power, manage, EOI";
$page_author = "COS10026 Group 03";
$current_page = "manage";
include "header.inc";
?>
  <main id="main-content">
    <h1>Manage applications</h1>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION["username"]); ?>. <a href="logout.php">Log out</a></p>
    <p>TODO: query forms and results table.</p>
  </main>
<?php include "footer.inc"; ?>
