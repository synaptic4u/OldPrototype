<?php if ((int) $status >= 1) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $hierachyName; ?> particulars was successfully
    updated.
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $hierachyName; ?>
    particulars was NOT
    updated.</span><?php } ?>