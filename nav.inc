<?php
// nav.inc – main menu. aria-current marks the page set in $current_page.
$menu = [
  'index' => 'Home',
  'jobs'  => 'Jobs',
  'apply' => 'Apply',
  'about' => 'About us',
];
?>
    <nav aria-label="Main menu">
      <ul>
<?php foreach ($menu as $file => $label): ?>
        <li><a href="<?php echo $file; ?>.php"<?php if (isset($current_page) && $current_page === $file) { echo ' aria-current="page"'; } ?>><?php echo $label; ?></a></li>
<?php endforeach; ?>
      </ul>
    </nav>
