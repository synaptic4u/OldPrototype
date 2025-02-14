send('<?php echo $particulars_for_body_id; ?>', '<?php echo $show; ?>', '<?php echo $particulars; ?>',
['<?php echo $hierachyid['value']; ?>','<?php echo $detid['value']; ?>']);
load.message('<?php echo $msg; ?>');
wipe.message();
scrollToEl('<?php echo $particulars_for_body_id; ?>');