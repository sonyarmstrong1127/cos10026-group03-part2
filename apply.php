<?php
// apply.php – Apply page (owner: Huy)
$page_title       = 'Apply';
$page_description = 'Apply for a job at Harvest Power, a renewable energy company.';
$page_keywords    = 'Harvest Power, apply, job application, careers';
$page_author      = 'Phan Quang Huy, COS10026 Group 03';
$current_page     = 'apply';
$page_style       = <<<CSS
    .form-note { font-style: italic; }
CSS;
include 'header.inc';
?>

<!-- HTML validation is turned off on purpose (novalidate, no required/pattern/maxlength):
     process_eoi.php checks every field on the server. -->

  <main id="main-content">
    <h1>Apply for a job</h1>
    <p class="form-note" style="margin-top: 0;">All fields are required except "Other skills". Find the job reference number on the <a href="jobs.php">jobs page</a>.</p>

    <form class="apply-form" method="post" action="process_eoi.php" novalidate="novalidate">

      <fieldset>
        <legend>Job</legend>
        <div class="form-row">
          <label for="jobref">Job reference number</label>
          <input type="text" id="jobref" name="jobref">
        </div>
      </fieldset>

      <fieldset>
        <legend>Personal details</legend>
        <div class="form-row">
          <label for="firstname">First name</label>
          <input type="text" id="firstname" name="firstname">
        </div>
        <div class="form-row">
          <label for="lastname">Last name</label>
          <input type="text" id="lastname" name="lastname">
        </div>
        <div class="form-row">
          <label for="dob">Date of birth</label>
          <input type="text" id="dob" name="dob" placeholder="dd/mm/yyyy">
        </div>
        <fieldset class="choice-group">
          <legend>Gender</legend>
          <span class="choice"><input type="radio" id="gender-female" name="gender" value="female"> <label for="gender-female">Female</label></span>
          <span class="choice"><input type="radio" id="gender-male" name="gender" value="male"> <label for="gender-male">Male</label></span>
          <span class="choice"><input type="radio" id="gender-other" name="gender" value="other"> <label for="gender-other">Other</label></span>
        </fieldset>
      </fieldset>

      <fieldset>
        <legend>Address</legend>
        <div class="form-row">
          <label for="street">Street address</label>
          <input type="text" id="street" name="street">
        </div>
        <div class="form-row">
          <label for="suburb">Suburb or town</label>
          <input type="text" id="suburb" name="suburb">
        </div>
        <div class="form-row">
          <label for="state">State</label>
          <select id="state" name="state">
            <option value="">Please select</option>
            <option value="VIC">VIC</option>
            <option value="NSW">NSW</option>
            <option value="QLD">QLD</option>
            <option value="NT">NT</option>
            <option value="WA">WA</option>
            <option value="SA">SA</option>
            <option value="TAS">TAS</option>
            <option value="ACT">ACT</option>
          </select>
        </div>
        <div class="form-row">
          <label for="postcode">Postcode</label>
          <input type="text" id="postcode" name="postcode">
        </div>
      </fieldset>

      <fieldset>
        <legend>Contact</legend>
        <div class="form-row">
          <label for="email">Email</label>
          <input type="text" id="email" name="email">
        </div>
        <div class="form-row">
          <label for="phone">Phone number</label>
          <input type="text" id="phone" name="phone">
        </div>
      </fieldset>

      <fieldset>
        <legend>Skills</legend>
        <fieldset class="choice-group">
          <legend>Select your skills</legend>
          <span class="choice"><input type="checkbox" id="skill-html" name="skills[]" value="html"> <label for="skill-html">HTML5</label></span>
          <span class="choice"><input type="checkbox" id="skill-css" name="skills[]" value="css"> <label for="skill-css">CSS3</label></span>
          <span class="choice"><input type="checkbox" id="skill-solar" name="skills[]" value="solar"> <label for="skill-solar">Solar systems</label></span>
          <span class="choice"><input type="checkbox" id="skill-data" name="skills[]" value="data"> <label for="skill-data">Data analysis</label></span>
          <span class="choice"><input type="checkbox" id="skill-other" name="skills[]" value="other"> <label for="skill-other">Other skills</label></span>
        </fieldset>
        <div class="form-row">
          <label for="otherskills">Other skills</label>
          <textarea id="otherskills" name="otherskills" rows="4" cols="40" placeholder="Tell us about any other skills"></textarea>
        </div>
      </fieldset>

      <div class="form-buttons">
        <input type="submit" value="Apply">
        <input type="reset" value="Reset form">
      </div>
    </form>
  </main>

<?php include 'footer.inc'; ?>
