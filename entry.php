<details>
  <summary>
    <?php echo getElement($entry, "Date"); ?>
    -- Journal Entry (Harris &amp; Johnston, <em>Journals</em>, 
    <?php echo $journal; ?>: <?php echo getElement($entry, "Source"); ?>.)
  </summary>
  <div><?php echo getElement($entry, "Journal Entry"); ?></div>
</details>
