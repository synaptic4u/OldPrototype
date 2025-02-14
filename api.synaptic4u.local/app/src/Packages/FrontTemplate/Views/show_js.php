navTogglerCheck();
setStyle('topbar', 'display:block;');
setStyle('sidebar', 'display:block;');
send('applications_for_navbar','<?php echo $loadApplications; ?>','<?php echo $Applications; ?>',['<?php echo $userid; ?>'], 1);