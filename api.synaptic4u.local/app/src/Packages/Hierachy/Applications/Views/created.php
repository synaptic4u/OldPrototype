<?php if (isset($rowcount) && (int) $rowcount > 0) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $appname; ?> was successfully
    subscribed to.
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $appname; ?>
    was NOT
    subscribed to.</span><?php } ?>