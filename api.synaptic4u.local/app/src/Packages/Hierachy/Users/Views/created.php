<?php if (isset($userid['value']) && (int) $userid['value'] > 0) { ?>
<span class="p-1 alert sm alert-success text-center fading"><?php echo $firstname.' '.$surname; ?> was successfully added.<br><?php echo $invited; ?></span>
<?php } if(isset($userid['value']) && (int) $userid['value'] === 0) { ?>
<span class="p-1 alert sm alert-warning text-center fading"><?php echo $firstname.' '.$surname; ?> was NOT added.<br><?php echo $invited; ?></span>
<?php } ?> 