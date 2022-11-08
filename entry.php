<details id="<?php echo $year.'-'.date('m',strtotime($month)).'-'.getDay($entry) ; ?>">
  <summary>
    <?php echo getElement($entry, "Date"); ?>
    -- Journal Entry (Harris &amp; Johnston, <em>Journals</em>, 
    <?php echo getElement($entry, "Source"); ?>: <?php echo getElement($entry, "Page"); ?>.)
  </summary>
  <div><?php echo getElement($entry, "Journal Entry"); ?></div>
</details>
