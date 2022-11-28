<!DOCTYPE html>
<?php
 require "functions.php";
 $resp = CallAPI("GET", "https://georgeeliotarchive.org/api/items/3155");
 $entry = json_decode($resp, true);
 ?>
<html>
  <head>
    <title>
      <?php echo getElement($entry, "Title");?>
    </title>
    <?php require "common_head.php";?>
  </head>
  <body>
    <header class="topbar">
      <h1>
        <?php echo getElement($entry, "Title");?>
      </h1>
    </header>
    <?php require "single_entry_body.php";?>
  </body>
</html>
