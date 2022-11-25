<?php $anchor = getYear($entry).'-'.date('m',strtotime($month)).'-'.getDay($entry) ; ?>
<style>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
<details id="<?php echo $anchor; ?>">
  <summary>
    <?php echo getElement($entry, "Date"); ?>
    -- Journal Entry (Harris &amp; Johnston, <em>Journals</em>, 
    <?php echo getElement($entry, "Source"); ?>: <?php echo getElement($entry, "Page"); ?>.)
    <div class="tooltip">
      <button id="<?php echo $anchor; ?>" onclick="replyId(this.id)">
      <span class="tooltiptext" id="<?php echo $anchor; ?>tooltip">Copy to clipboard</span>
      &#10697;
    </button>
</div>
  </summary>
  <div><?php echo getElement($entry, "Journal Entry"); ?></div>
</details>

<script>
  // https://stackoverflow.com/q/4825295
  function replyId(clicked_id) {
    // removes anchor (if any) and adds anchor
    let url = window.location.href.split('#')[0] + "#" + clicked_id;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(url);
    } else {
      url.select();
      document.execCommand("Copy");
    }
    
    // change tooltip text
    let tooltip = document.getElementById(clicked_id + "tooltip");
    tooltip.innerHTML = "Copied";
  }
</script>