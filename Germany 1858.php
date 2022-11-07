<!DOCTYPE html>
<?php
 require "functions.php";
 $journal = "Germany 1858";

 $resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
 $data = json_decode($resp, true);
 $entries = getJournalEntries($data, $journal);
 $months = arrangeEntriesByMonth($entries);
 ?>
<html>
  <head>
    <title>
      <?php
       echo $journal;
       ?>
    </title>
    <?php require "common_head.php";?>
  </head>
  <body>
    <?php require "single_year_body.php";?>
  </body>
</html>
