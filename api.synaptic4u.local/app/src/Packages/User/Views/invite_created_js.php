<?php
    if ((int)$userid > 0) {
?>
setHTML('val', '<?php echo $calls_userid; ?>');
setTimeout(function(){
send('init','<?php echo $login; ?>','<?php echo $user; ?>', {
"email":"<?php echo $email; ?>", "passkey":"<?php echo $password; ?>"
});
},4000);
<?php
    } else {
?>
setTimeout(function(){
window.location.href= 'index.php?message=Try to register again!';
},3000);
<?php
    }
?>