<?php
// about.php – About page. Team details, contributions, quotes and fun facts
// are loaded from the about table (see harvest_power.sql).
// OWNER: Hai (Part 1 page) – database version completed by Huy
require_once 'settings.php';

$page_title       = 'About us';
$page_description = 'Meet Harvest Power Group 03, our team members, contributions and interesting facts.';
$page_keywords    = 'Harvest Power, Group 03, team, COS10026, About Us';
$page_author      = 'COS10026 Group 03';
$current_page     = 'about';
$page_style       = <<<CSS
    .about-section {
    margin-bottom: 2em;
    }
    .group-photo {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    }
CSS;

// Print a value from the database safely, or a placeholder when it is empty
function show($value, $placeholder = 'Not provided') {
    if ($value === null || trim($value) === '') {
        return $placeholder;
    }
    return htmlspecialchars($value);
}

// Read all team members from the about table
$members  = [];
$db_error = '';
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    $db_error = 'Team details are not available right now. Please try again later.';
} else {
    mysqli_set_charset($conn, 'utf8mb4');
    $result = mysqli_query($conn, 'SELECT * FROM about ORDER BY member_id');
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}

include 'header.inc';
?>

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

<?php if ($db_error !== ''): ?>
  <p><?php echo htmlspecialchars($db_error); ?></p>
<?php else: ?>
  <!-- Our Team: one article per row of the about table -->
  <section class="about-section">
    <h2>Our Team</h2>
<?php foreach ($members as $m): ?>
    <article class="team-member">
      <h3><?php echo show($m['member_name']); ?></h3>
      <p><strong>Student ID:</strong> <?php echo show($m['student_id']); ?></p>
      <p><strong>Part 1 contribution:</strong> <?php echo show($m['part1_contribution']); ?></p>
      <p><strong>Part 2 contribution:</strong> <?php echo show($m['part2_contribution']); ?></p>
    </article>
<?php endforeach; ?>
  </section>

  <!-- Our Quotes: first language + English translation -->
  <section class="about-section">
    <h2>Our Quotes</h2>
<?php foreach ($members as $m): ?>
    <article class="member-quote">
      <h3><?php echo show($m['member_name']); ?></h3>
<?php if (!empty($m['quote_vi'])): ?>
      <blockquote lang="vi">
        <p><?php echo htmlspecialchars($m['quote_vi']); ?></p>
      </blockquote>
      <p><strong>English translation:</strong> <?php echo show($m['quote_en']); ?></p>
<?php else: ?>
      <p><em>Quote not provided.</em></p>
<?php endif; ?>
    </article>
<?php endforeach; ?>
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
<?php foreach ($members as $m): ?>
        <tr>
          <th scope="row"><?php echo show($m['member_name']); ?></th>
          <td><?php echo show($m['hobby']); ?></td>
          <td><?php echo show($m['favourite_food']); ?></td>
          <td><?php echo show($m['fun_fact']); ?></td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </section>
<?php endif; ?>

  <!-- Group Photo -->
  <section class="about-section">
    <h2>Our Group Photo</h2>
    <figure>
      <img class="group-photo" src="images/group-photo.jpg" width="1200" height="679"
           alt="Video call screenshot of the three Group 03 members: Hoàng Hải, Nghĩa Trần and Quang Huy">
      <figcaption>Group 03 meeting on Discord, November 2026.</figcaption>
    </figure>
  </section>
  </main>

<?php include 'footer.inc'; ?>
