load.message('<?php echo $msg; ?>');
wipe.message();
<?php if (!is_null($rowcount) && (int)$rowcount > 0) { ?>
    send('<?php echo $types_for_body_id; ?>','<?php echo $show; ?>','<?php echo $types; ?>',{'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>'});
<?php } else { ?>
    send('<?php echo $types_for_body_id; ?>','<?php echo $edit; ?>','<?php echo $types; ?>',{'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'hierachytypeid' : '<?php echo $hierachytypeid['value']; ?>'});
<?php }