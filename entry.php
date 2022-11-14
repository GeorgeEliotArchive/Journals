<?php $anchor = $year.'-'.date('m',strtotime($month)).'-'.getDay($entry) ; ?>
<details id="<?php echo $anchor; ?>">
  <summary>
    <?php echo getElement($entry, "Date"); ?>
    -- Journal Entry (Harris &amp; Johnston, <em>Journals</em>, 
    <?php echo getElement($entry, "Source"); ?>: <?php echo getElement($entry, "Page"); ?>.)
    <button id="<?php echo $anchor; ?>" onClick="replyId(this.id)">&#10697;</button>
  </summary>
  <div><?php echo getElement($entry, "Journal Entry"); ?></div>
</details>

<script>
  // https://stackoverflow.com/q/4825295
  function replyId(clicked_id) {
    // removes anchor (if any) and adds anchor
    let url = window.location.href.split('#')[0] + "#" + clicked_id;
    window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
  }
</script>