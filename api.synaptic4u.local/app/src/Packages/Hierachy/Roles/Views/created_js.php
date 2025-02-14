send('<?php echo $roles_for_body_id; ?>', '<?php echo $show; ?>', '<?php echo $roles; ?>',
['<?php echo $hierachyid['value']; ?>','<?php echo $detid['value']; ?>']);
setHTML('<?php echo $roles_for_body_id; ?>','');
load.message('<?php echo $msg; ?>');
wipe.message();
