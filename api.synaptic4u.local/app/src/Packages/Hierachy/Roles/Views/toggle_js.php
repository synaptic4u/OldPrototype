load.message('<?php echo $msg; ?>');
setTimeout(function(){
        window.scrollTo({
            top : document.getElementById('<?php echo $roles_for_body_id; ?>').offsetTop - (document.getElementById("main_container").offsetTop-6),
            left : 0,
            behavior :'smooth'
        });
    }, 2000);
wipe.message();