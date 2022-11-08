<!DOCTYPE html>
<?php
 require "functions.php";
 $url_params = $_GET ?? array();

 $resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
 $data = json_decode($resp, true);
 $entries = getSearchResults($data, $url_params);
 $years = arrangeEntriesByYear($entries);
 ?>
<html>
  <head>
    <title>Search Results</title>
    <?php require "common_head.php"; ?>
  </head>
  <body>
    <header class="topbar">
      <h1><?php echo json_encode($url_params); ?></h1>
    </header>
    <?php
     if (count($years) > 0)
         require "multi_year_body.php";
     else { ?>
      <h3>No results found for your query.</h3>
    <?php } ?>
  </body>
</html>
