<?php
 // For each year, display the year in detail tag.
 foreach ($years as $year => $months) {
?>
<details id="<?php echo $year; ?>">
  <summary><?php echo $year;?></summary>
  <?php
   // For each month, display the month in detail tag.
   foreach (array_keys($months) as $month) {
   $monthEntries = $months[$month];
   ?>
  <details id="<?php echo date('m',strtotime($month)); ?>">
    <summary><?php echo $month;?> (<?php echo sizeof($monthEntries);?> Items)</summary>
    <div>
      <?php 
       // For each entry, display the date in detail tag.
       foreach ($monthEntries as $entry) { 
       ?>
      <?php require "entry.php";?>
      <?php } /* end foreach entry */?>
    </div>
  </details>
  <?php } /* end foreach month */?>
</details>
<?php } /* end foreach year */?>
<script type="text/javascript" src="./open_entry.js"></script>
