send('<?php echo $applications_for_body_id; ?>', '<?php echo $show; ?>', '<?php echo $applications; ?>',
['<?php echo $hierachyid['value']; ?>','<?php echo $detid['value']; ?>']);
setHTML('<?php echo $applications_for_body_id; ?>','');
load.message('<?php echo $msg; ?>');
wipe.message();
scrollToEl('<?php echo $applications_for_body_id; ?>');