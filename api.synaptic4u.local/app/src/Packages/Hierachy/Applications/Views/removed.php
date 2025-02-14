<?php if (isset($rowcount) && (int) $rowcount > 0) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $appname; ?> was successfully
    removed.
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $appname; ?>
    was NOT
    removed.</span><?php } ?>