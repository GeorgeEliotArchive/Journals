<div class="entry"><?php echo getElement($entry, "Journal Entry"); ?></div>
<?php $footnotes = getElement($entry, "Footnotes");
 if ($footnotes != "Element missing: Footnotes!") {
 ?>
     <div class="footnotes"><?php echo getElement($entry, "Footnotes"); ?></div>
<?php } /* end if */ ?>
