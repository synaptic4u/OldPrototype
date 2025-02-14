<?php if (((int) $count > 0) || ((int) $countdet > 0) ) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $hierachyname; ?> was successfully updated.
</span><?php } else { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $hierachyname; ?> was NOT
    updated.</span><?php } ?>