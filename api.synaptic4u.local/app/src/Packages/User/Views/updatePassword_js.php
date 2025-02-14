function verify(id) {
    let pass1 = document.getElementById("login_password");
    let pass2 = document.getElementById("login_password_verify");
    let form = document.getElementById('User');
    let divz = form.getElementsByClassName('invalid-feedback');

    pass1.classList.remove('is-valid');
    pass1.classList.remove('is-invalid');
    pass2.classList.remove('is-valid');
    pass2.classList.remove('is-invalid');

    divz[0].innerHTML = 'A minimum of 15 character is required.<br>No "<" or ">" characters allowed.';
    divz[1].innerHTML = 'A minimum of 15 character is required.<br>No "<" or ">" characters allowed.';

    if ((pass1.value === pass2.value) && (pass1.value.length >= 15)) {
        grab('body', '<?php echo $calls['storePassword']; ?>', '<?php echo $calls['User']; ?>', 'User');
    } else {

        divz[0].innerHTML += '<br>Passwords need to be the same!';
        divz[1].innerHTML += '<br>Passwords need to be the same!';

        pass1.classList.add('is-invalid');
        pass2.classList.add('is-invalid');

        
    }
}