send('<?php echo $hierachy_list; ?>', '<?php echo $show; ?>', '<?php echo $hierachy; ?>');
setHTML('hierachy-form','');
load.message('<?php echo $msg; ?>');
wipe.message();
scrollToEl('OrganizationDetails');