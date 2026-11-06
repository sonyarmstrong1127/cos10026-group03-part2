<?php
// about.php – About page (owner: Hai)
$page_title       = 'About us';
$page_description = 'Meet Harvest Power Group 03, our team members, contributions and interesting facts.';
$page_keywords    = 'Harvest Power, Group 03, team, COS10026, About Us';
$page_author      = 'COS10026 Group 03';
$current_page     = 'about';
$page_style       = <<<CSS
    .about-section {
    margin-bottom: 2em;
    }
CSS;
include 'header.inc';
?>

<!--
  TODO (Part 2):
  - Load the member details and contributions from the about table (see harvest_power.sql)
  -   instead of the hard-coded "Our Team" section, and add each member\'s Part 2 contribution.
-->

  <main id="main-content">
  <h1>About Us</h1>

  <!-- About Our Group -->
  <section class="about-section">
    <h2>About Our Group</h2>

    <p>
      We are Group 03, a team of three students working
      together to develop the Harvest Power website for
      COS10026 Applied Web Project.
    </p>

    <p>
      Our group class is held every Saturday from
      7:00 AM to 10:00 AM.
    </p>
  </section>

  <!-- Our Team -->
  <section class="about-section">
    
    <h2>Our Team</h2>

    <article class="team-member">
      <h3>Phan Quang Huy</h3>
      <p><strong>Student ID:</strong> 106219394</p>
      <p><strong>Contribution:</strong> Responsible for developing the Home and Apply pages.</p>
    </article>

    <article class="team-member">
      <h3>Trần Tấn Nghĩa</h3>
      <p><strong>Student ID:</strong> 106394660</p>
      <p><strong>Contribution:</strong> Responsible for developing the Jobs page.</p>
    </article>

    <article class="team-member">
      <h3>Lê Phạm Hoàng Hải</h3>
      <p><strong>Student ID:</strong> 105997220</p>
      <p><strong>Contribution:</strong> Responsible for developing the About Us page.</p>
    </article>
  </section>
  
<!-- Our Quotes -->
<section class="about-section">
  <h2>Our Quotes</h2>

  <article class="member-quote">
    <h3>Phan Quang Huy</h3>
    <blockquote lang="vi">
      <p>Cười thật lớn, chạy thật nhanh, sống tử tế.</p>
    </blockquote>
    <p><strong>English translation:</strong> Laugh hard, run fast, be kind.</p>
  </article>

  <article class="member-quote">
    <h3>Trần Tấn Nghĩa</h3>
    <p class="quote-missing" style="color: #555555;"><em>Quote not provided by this member before the submission deadline.</em></p>
  </article>

  <article class="member-quote">
    <h3>Lê Phạm Hoàng Hải</h3>
    <p class="quote-missing" style="color: #555555;"><em>Quote not provided by this member before the submission deadline.</em></p>
  </article>
</section>
  
<!-- Fun Facts -->
<section class="about-section">
  <h2>Fun Facts About Our Team</h2>

  <table class="fun-facts-table">
    <caption>Interesting facts about our team members</caption>

    <thead>
      <tr>
        <th scope="col">Member</th>
        <th scope="col">Favourite Hobby</th>
        <th scope="col">Favourite Food</th>
        <th scope="col">Interesting Fact</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Phan Quang Huy</th>
        <td>Watching F1</td>
        <td>Nem Nướng</td>
        <td>I like black coffee</td>
      </tr>

      <tr>
        <th scope="row">Trần Tấn Nghĩa</th>
        <td>Not provided</td>
        <td>Not provided</td>
        <td>Not provided</td>
      </tr>

      <tr>
        <th scope="row">Lê Phạm Hoàng Hải</th>
        <td>Not provided</td>
        <td>Not provided</td>
        <td>Not provided</td>
      </tr>
    </tbody>
  </table>
</section>
  
<!-- Group Photo -->
<section class="about-section">
  <h2>Our Group Photo</h2>

  <p>A group photo is not available: the team could not meet in person or on a video call before the submission deadline.</p>
</section>


   
  </main>

<?php include 'footer.inc'; ?>
