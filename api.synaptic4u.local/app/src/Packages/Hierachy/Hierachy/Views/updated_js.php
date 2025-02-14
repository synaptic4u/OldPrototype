send('<?php echo $hierachy_det_form_id; ?>', '<?php echo $subshow; ?>', '<?php echo $subhierachy; ?>',
['<?php echo $hierachyid; ?>','<?php echo $detid; ?>']);
setClassInnerText('header-hierachy-name','<?php echo $hierachyname; ?>');
setClassInnerText('header-hierachy-creator','<?php echo $hierachycreator; ?>');
pause.short(send('<?php echo $hierachy_list; ?>', '<?php echo $show; ?>', '<?php echo $hierachy; ?>'));
load.message('<?php echo $msg; ?>');
wipe.message();
scrollToEl('OrganizationDetails');
clickButton('hierachy-button');