<?php if (isset($particularid) && (int) $particularid > 0) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $hierachyName; ?> particulars was successfully
    created.
    <?php if (isset($imageid) && (int) $imageid['value'] > 0) { ?><span
        class="p-1 alert sm alert-success text-center fading"><?php echo $hierachyName; ?> particulars logo successfully
        created.
    </span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $hierachyName; ?>
        particulars logo was NOT
        created.</span><?php } ?>
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $hierachyName; ?>
    particulars was NOT
    created.</span><?php } ?>