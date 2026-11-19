<?php
// jobs.php – Jobs page (owner: Nghia)
require_once 'settings.php';

$page_title       = 'Job Vacancies';
$page_description = 'Current job vacancies at Harvest Power, a renewable energy company.';
$page_keywords    = 'Harvest Power, jobs, careers, renewable energy, engineer, content officer';
$page_author      = 'Tran Tan Nghia, COS10026 Group 03';
$current_page     = 'jobs';
$page_style       = <<<CSS
    .jobs-header-title {
        color: #1b5e20;
        border-bottom: 2px solid #1b5e20;
        padding-bottom: 0.3em;
    }
    .jobs-search-form {
        margin: 1.5em 0;
        display: flex;
        gap: 0.5em;
        flex-wrap: wrap;
        align-items: center;
    }
    .jobs-search-input {
        padding: 0.6em;
        border: 1px solid #ccc;
        border-radius: 4px;
        flex: 1;
        min-width: 220px;
    }
    .jobs-search-btn {
        padding: 0.6em 1.2em;
        background-color: #1b5e20;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .jobs-search-btn:hover {
        background-color: #144618;
    }
    .jobs-clear-link {
        color: #1b5e20;
        text-decoration: underline;
        margin-left: 0.5em;
    }
CSS;
include 'header.inc';

// Capture and sanitize the search query
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$jobs = [];
$db_error = '';

// Connect to the database (assumes $host, $user, $pwd, $sql_db from settings.php)
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    $db_error = "Unable to connect to the database. Please try again later.";
} else {
    if ($search_query !== '') {
        // Search across title, reference number, and description using prepared statements
        $sql = "SELECT * FROM jobs 
                WHERE title LIKE ? 
                   OR job_ref LIKE ? 
                   OR short_description LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);
        $like_param = '%' . $search_query . '%';
        mysqli_stmt_bind_param($stmt, 'sss', $like_param, $like_param, $like_param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        // Fetch all jobs if no search term is entered
        $sql = "SELECT * FROM jobs";
        $result = mysqli_query($conn, $sql);
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}
?>

  <main id="main-content" class="jobs-container">

    <h2>Inclusive Employment</h2>
    <p>Harvest Power welcomes applications from Aboriginal and Torres Strait Islander peoples. We work on Country, so we want our team to include the voices of its Traditional Custodians. Tell us in your application if you need any adjustments during the recruitment process.</p>
    
    <aside class="jobs-aside">
      <h2>Why Join Our Team?</h2>
      <p>We are a forward-thinking renewable energy group dedicated to professional ethics, digital privacy compliance, and sustainable carbon-reduction design choices.</p>
      <p>All candidates must submit matching reference codes through our standardized application workflow.</p>
    </aside>
    
    <!-- Primary Content Area -->
    <div class="jobs-main-content">
      <h1 class="jobs-header-title">Career Opportunities in Clean Energy</h1>
      <p>Join our mission to strengthen renewable technologies, manage smart grids, and deliver public engagement initiatives for clean energy solutions.</p>

      <!-- Search Form -->
      <form method="get" action="jobs.php" class="jobs-search-form" role="search">
        <label for="job-search" class="visually-hidden">Search jobs:</label>
        <input 
          type="text" 
          id="job-search" 
          name="q" 
          class="jobs-search-input"
          placeholder="Search by role, keyword, or ref (e.g., RE109)..." 
          value="<?php echo htmlspecialchars($search_query); ?>"
        >
        <button type="submit" class="jobs-search-btn">Search</button>
        <?php if ($search_query !== ''): ?>
          <a href="jobs.php" class="jobs-clear-link">Clear</a>
        <?php endif; ?>
      </form>

      <?php if ($db_error !== ''): ?>
        <p class="error-message"><?php echo htmlspecialchars($db_error); ?></p>
      <?php elseif (empty($jobs)): ?>
        <p>No job vacancies found matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>".</p>
      <?php else: ?>
        <?php foreach ($jobs as $job): ?>
          <section class="jobs-section">
            <h2><?php echo htmlspecialchars($job['title']); ?></h2>
            
            <p><strong>Job Reference Number:</strong> <code style="background-color: #f0f0f0; padding: 0.2em 0.4em; border-radius: 3px;"><?php echo htmlspecialchars($job['job_ref']); ?></code></p>
            
            <p><strong>Short Description:</strong> <?php echo htmlspecialchars($job['short_description']); ?></p>
            
            <h3>Role Position Details</h3>
            <ul>
              <li><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></li>
              <li><strong>Reporting Line:</strong> <?php echo htmlspecialchars($job['reporting_line']); ?></li>
            </ul>

            <h3>Key Responsibilities</h3>
            <ul>
              <?php 
              // Splits newline-separated items from the database into list items
              $responsibilities = array_filter(explode("\n", $job['responsibilities']));
              foreach ($responsibilities as $item): 
              ?>
                <li><?php echo htmlspecialchars(trim($item)); ?></li>
              <?php endforeach; ?>
            </ul>

            <h3>Position Requirements</h3>
            <ul>
              <li><strong>Essential:</strong> <?php echo htmlspecialchars($job['essential_req']); ?></li>
              <li><strong>Preferable:</strong> <?php echo htmlspecialchars($job['preferable_req']); ?></li>
            </ul>
          </section>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>

<?php include 'footer.inc'; ?>


    </main>

<?php include 'footer.inc'; ?>
