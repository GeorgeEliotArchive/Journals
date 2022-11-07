<!DOCTYPE html>
<?php
 require "functions.php";
 $journal = "Diary 1879";

 $resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items?collection=17");
 $data = json_decode($resp, true);
 $entries = getJournalEntries($data, $journal);
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
    <header class="topbar">
      <h1>
        <?php echo $journal; ?>
      </h1>
    </header>
    <?php require "single_year_body.php";?>
  </body>
</html>
