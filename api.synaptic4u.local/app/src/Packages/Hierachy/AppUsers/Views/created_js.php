send('<?php echo $app_roles_row_user; ?>', '<?php echo $show; ?>', '<?php echo $appusers; ?>',
{'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'appid' : '<?php echo $appid['value']; ?>', 'moduleid' : '<?php echo $moduleid['value']; ?>'});
setHTML('<?php echo $app_roles_row_user; ?>','');
load.message('<?php echo $msg; ?>');
wipe.message();
 