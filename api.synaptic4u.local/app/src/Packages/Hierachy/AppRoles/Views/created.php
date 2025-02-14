<?php if ((int) $rowcount > 0) { ?>
<span class="p-1 alert sm alert-success text-center fading"><?php echo $role; ?> was successfully added.</span>
<?php } else { ?>
<span class="p-1 alert sm alert-warning text-center fading"><?php echo $role; ?> was NOT added.</span>
<?php } ?>  