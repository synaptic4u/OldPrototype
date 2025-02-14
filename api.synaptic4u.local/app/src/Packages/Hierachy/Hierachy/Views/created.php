<?php if (isset($detid) && (int) $detid > 0) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $hierachyName; ?> was successfully created.
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $hierachyName; ?> was NOT
    created.</span><?php } ?>