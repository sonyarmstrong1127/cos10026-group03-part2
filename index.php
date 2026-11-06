<?php
// index.php – Home page (owner: Huy)
$page_title       = 'Home';
$page_description = 'Harvest Power is a renewable energy company that builds solar, wind and bioenergy projects with local communities.';
$page_keywords    = 'Harvest Power, renewable energy, solar, wind, bioenergy, clean energy, careers';
$page_author      = 'Phan Quang Huy, COS10026 Group 03';
$current_page     = 'index';
$page_style       = <<<CSS
    .slogan { font-style: italic; }
CSS;
include 'header.inc';
?>

  <main id="main-content">

    <section class="home-hero" aria-labelledby="home-title">
      <h1 id="home-title">Harvest Power</h1>
      <p class="slogan">Clean energy, harvested from nature.</p>
      <p>Harvest Power is a renewable energy company. We plan, build and run solar farms, wind turbines and bioenergy plants that turn farm waste into power. We work with farmers, councils and local communities so that clean energy is affordable and shared fairly.</p>
      <img src="images/home-energy.jpg" alt="Wind turbines standing in a yellow flower field under a blue sky" width="600" height="300">
    </section>

    <section aria-labelledby="services-title">
      <h2 id="services-title">What we do</h2>
      <table class="services-table">
        <caption>Harvest Power services</caption>
        <thead>
          <tr>
            <th scope="col" rowspan="2">Service</th>
            <th scope="colgroup" colspan="2">Details</th>
          </tr>
          <tr>
            <th scope="col">What it is</th>
            <th scope="col">Who it helps</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">Solar farms</th>
            <td>Large solar panel sites built on unused land</td>
            <td>Towns and regional councils</td>
          </tr>
          <tr>
            <th scope="row">Wind energy</th>
            <td>Wind turbines planned together with local people</td>
            <td>Regional communities and industry</td>
          </tr>
          <tr>
            <th scope="row">Bioenergy</th>
            <td>Biogas made from farm and food waste</td>
            <td>Farmers and food producers</td>
          </tr>
          <tr>
            <th scope="row">Community programs</th>
            <td colspan="2">Free workshops and open days about saving energy and using clean power at home</td>
          </tr>
        </tbody>
      </table>
    </section>

    <section aria-labelledby="search-title">
      <h2 id="search-title">Search our site</h2>
      <form class="search-form" action="jobs.php" method="get" role="search">
        <label for="site-search">Search for a job or topic</label>
        <input type="search" id="site-search" name="q" maxlength="50" placeholder="e.g. engineer">
        <button type="submit">Search</button>
      </form>
    </section>

    <section class="acknowledgement" aria-labelledby="ack-title">
      <h2 id="ack-title">Acknowledgement of Country</h2>
      <p>Harvest Power acknowledges the Traditional Custodians of the lands on which we work and live, and we pay our respects to Elders past and present. Aboriginal and Torres Strait Islander peoples have cared for Country for tens of thousands of years. We want our energy projects to respect that care, and we talk with Traditional Custodians before we plan any project on their land.</p>
      <p style="font-weight: bold;">We welcome job applications from Aboriginal and Torres Strait Islander peoples. <a href="jobs.php">See our current jobs</a>.</p>
    </section>
  </main>

<?php include 'footer.inc'; ?>
