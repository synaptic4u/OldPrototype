send('<?php echo $types_for_body_id; ?>', '<?php echo $show; ?>', '<?php echo $types; ?>',
['<?php echo $hierachyid['value']; ?>','<?php echo $detid['value']; ?>']);
setHTML('<?php echo $types_for_body_id; ?>','');
load.message('<?php echo $msg; ?>');
wipe.message();
