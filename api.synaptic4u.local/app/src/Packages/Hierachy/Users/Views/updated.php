<?php if ((int) $rowcount >= 1 || (int)$count >= 1) { ?>
<span class="p-1 alert sm alert-success text-center fading"><?php echo $firstname.' '.$surname; ?> was successfully updated
<?php } else { ?>
<span class="p-1 alert sm alert-warning text-center fading"><?php echo $firstname.' '.$surname; ?> was NOT updated
<?php } 
if( ( (int)$inviteid > 0 || (int)$noteid > 0) && ( (int) $rowcount >= 1 || (int)$count >= 1) ){ ?> 
<br>and have been invited to join <?php echo $hierachyname; ?>.</span>
<?php }else{ ?>
.</span>
<?php }?>