<header class="topbar">
  <h1>
    <?php echo $journal; ?>
  </h1>
</header>
<?php
 foreach (array_keys($months) as $month) {
 $monthEntries = $months[$month];
 ?>
<details>
  <summary><?php echo $month;?> (<?php echo sizeof($monthEntries);?> Items)</summary>
  <div>
    <?php foreach ($monthEntries as $entry) { ?>
    <?php require "entry.php"; ?>
    <?php } /* end foreach entry */?>
  </div>
</details>
<?php } /* end foreach month */?>
