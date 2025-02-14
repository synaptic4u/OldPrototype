document.getElementById('user-profile-name').innerHTML='<?php echo $firstname.' '.$surname; ?>';
send('main_container','<?php echo $show; ?>','<?php echo $user; ?>');
load.message('<?php echo $msg; ?>');
wipe.message();