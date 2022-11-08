<header class="topbar">
  <h1>
    <?php echo $journal; ?>
  </h1>
</header>
<?php
 foreach (array_keys($months) as $month) {
 $monthEntries = $months[$month];
 ?>
<details id="<?php echo date('m',strtotime($month)); ?>">
  <summary><?php echo $month;?> (<?php echo sizeof($monthEntries);?> Items)</summary>
  <div>
    <?php foreach ($monthEntries as $entry) { ?>
    <?php require "entry.php"; ?>
    <?php } /* end foreach entry */?>
  </div>
</details>
<?php } /* end foreach month */?>
<script type="text/javascript" src="./open_entry.js"/>
