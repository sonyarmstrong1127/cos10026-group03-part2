<?php
// jobs.php – Jobs page (owner: Nghia)
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
CSS;
include 'header.inc';
?>

<!--
  TODO (Part 2):
  - Read the jobs from the jobs table (see harvest_power.sql) and print each job with a PHP loop,
  -   instead of the two hard-coded <section class="jobs-section"> blocks below.
  - Add a search bar on this page that searches the jobs table (e.g. GET jobs.php?q=engineer or search.php).
  - Remember: escape every value printed from the database with htmlspecialchars().
-->

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

      <!-- Position 1: Section structural element -->
      <section class="jobs-section">
        <h2>Renewable Energy Systems Engineer</h2>
        
        <p><strong>Job Reference Number:</strong> <code style="background-color: #f0f0f0; padding: 0.2em 0.4em; border-radius: 3px;">RE109</code></p>
        
        <p><strong>Short Description:</strong> We are looking for a skilled engineer to build, optimize, and evaluate commercial solar and wind infrastructure platforms while maintaining clean system data workflows.</p>
        
        <h3>Role Position Details</h3>
        <ul>
          <li><strong>Salary:</strong> $95,000 - $112,000 AUD per annum</li>
          <li><strong>Reporting Line:</strong> Reports directly to the Principal Projects Director</li>
        </ul>

        <h3>Key Responsibilities</h3>
        <ol>
          <li>Design and test operational models for commercial clean energy integration.</li>
          <li>Collaborate with web developers to present real-time performance analytics on community portals.</li>
          <li>Review public safety protocols and system security updates systematically.</li>
        </ol>

        <h3>Position Requirements</h3>
        <ul>
          <li><strong>Essential:</strong> Bachelor’s degree in Engineering or Renewable Technologies, and 2+ years of grid-modeling experience.</li>
          <li><strong>Preferable:</strong> Familiarity with smart-grid monitoring tools and collaborative project management spaces like Jira.</li>
        </ul>
      </section>

      <!-- Position 2: Section structural element -->
      <section class="jobs-section">
        <h2>Digital Engagement Content Officer</h2>
        <p><strong>Job Reference Number:</strong> <code style="background-color: #f0f0f0; padding: 0.2em 0.4em; border-radius: 3px;">CO482</code></p>
        
        <p><strong>Short Description:</strong> Drive public engagement initiatives by maintaining our suite of client-facing platforms, writing clean environmental impact copy, and managing accessible campaign data structures.</p>
        
        <h3>Role Position Details</h3>
        <ul>
          <li><strong>Salary:</strong> $72,000 - $81,000 AUD per annum</li>
          <li><strong>Reporting Line:</strong> Reports directly to the Communications Strategy Manager</li>
        </ul>

        <h3>Key Responsibilities</h3>
        <ul>
          <li>Author accessible web copy detailing our latest project landmarks and carbon-neutral targets.</li>
          <li>Liaise directly with front-end technical groups to maintain structural page elements without broken links.</li>
          <li>Coordinate public community webinars focused on neighborhood green solutions.</li>
        </ul>

        <h3>Position Requirements</h3>
        <ul>
          <li><strong>Essential:</strong> Proven baseline background in corporate communications, clear writing mechanics, and fundamental awareness of web layout structures.</li>
          <li><strong>Preferable:</strong> Prior work experience inside a sustainable energy environment, or working knowledge of basic WCAG web readability strategies.</li>
        </ul>
      </section>
    </div>

    </main>

<?php include 'footer.inc'; ?>
