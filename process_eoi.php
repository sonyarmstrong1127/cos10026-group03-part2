<?php
// process_eoi.php – receives the Apply form and stores an Expression of Interest.
// OWNER: ___

// 1. Block direct URL access: only accept a POST from apply.php
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["jobref"])) {
    header("Location: apply.php");
    exit();
}

require_once "settings.php";

/*
  TODO (Part 2 brief, requirement 4):
  2. Sanitise every input: trim(), stripslashes(), htmlspecialchars().
  3. Validate every field on the server (the HTML form has no validation any more):
       jobref    exactly 5 letters/digits and must exist in the jobs table
       firstname letters only, max 20      lastname letters only, max 20
       dob       dd/mm/yyyy (store as DATE)  gender  female / male / other
       street    max 40   suburb max 40      state   one of VIC NSW QLD NT WA SA TAS ACT
       postcode  exactly 4 digits            email   valid format (filter_var)
       phone     8 to 12 digits or spaces    skills  at least one checkbox
       otherskills required if the "Other skills" checkbox is ticked
     If anything is wrong, show all error messages and a link back to apply.php.
  4. Connect with mysqli_connect($host, $user, $pwd, $sql_db).
  5. If the eoi table does not exist, create it in code (same CREATE TABLE as harvest_power.sql).
  6. INSERT the record with a prepared statement (status defaults to 'New').
  7. Show a confirmation page with the new EOInumber: mysqli_insert_id($conn).
*/

$page_title = "Application received";
$page_description = "Confirmation of your job application at Harvest Power.";
$page_keywords = "Harvest Power, application, confirmation";
$page_author = "COS10026 Group 03";
$current_page = "apply";
include "header.inc";
?>
  <main id="main-content">
    <h1>Application received</h1>
    <p>TODO: show validation errors or the EOInumber here.</p>
  </main>
<?php include "footer.inc"; ?>
