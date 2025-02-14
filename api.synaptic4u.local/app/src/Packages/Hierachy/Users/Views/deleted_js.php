load.message('<?php echo $msg; ?>');
send('<?php echo $users_for_body_id; ?>','<?php echo $show; ?>','<?php echo $users; ?>',{'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>'});
setHTML('<?php echo $users_for_body_id; ?>','');
wipe.message();
