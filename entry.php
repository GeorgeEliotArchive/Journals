<style>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 135px;
  background-color: #60605D;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 100%;
  left: 50%;
  margin-left: -67px;
  margin-bottom: 10px;
  opacity: 0;
  transition: opacity 0.5s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #60605D transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>

<?php $anchor = getYear($entry).'-'.date('m',strtotime($month)).'-'.getDay($entry) ; ?>
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
  <div class="entry"><?php echo getElement($entry, "Journal Entry"); ?></div>
  <?php $footnotes = getElement($entry, "Footnotes");
   if ($footnotes != "Element missing: Footnotes!") {
   ?>
       <div class="footnotes"><?php echo getElement($entry, "Footnotes"); ?></div>
  <?php } /* end if */ ?>
</details>

<script>
  // https://stackoverflow.com/q/4825295
  // store previous tooltip id to change it back after copying another link
  var prev_tooltip;
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
    if (prev_tooltip) {
      prev_tooltip.innerHTML = "Copy to clipboard";
    }
    let tooltip = document.getElementById(clicked_id + "tooltip");
    tooltip.innerHTML = "Copied";
    prev_tooltip = tooltip;
  }
</script>
