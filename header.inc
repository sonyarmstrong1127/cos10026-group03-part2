<?php
/*
  header.inc – shared <head>, skip link, site header and menu.
  Before including this file, a page sets:
    $page_title, $page_description, $page_keywords, $page_author,
    $current_page ('index', 'jobs', 'apply', 'about', 'manage' ...)
    $page_style (optional) – one small page-specific CSS rule
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
  <meta name="author" content="<?php echo htmlspecialchars($page_author); ?>">
  <title><?php echo htmlspecialchars($page_title); ?> | Harvest Power</title>
  <link rel="stylesheet" href="styles/style.css">
<?php if (!empty($page_style)) { echo "  <style>\n" . $page_style . "\n  </style>\n"; } ?>
</head>
<body>
  <a class="skip-link" href="#main-content">Skip to main content</a>

  <header>
    <p class="brand"><a href="index.php"><img src="images/logo.png" alt="Harvest Power logo: an orange pumpkin" width="48" height="48"> Harvest Power</a></p>
<?php include 'nav.inc'; ?>
  </header>
