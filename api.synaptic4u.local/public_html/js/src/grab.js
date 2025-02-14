/**
    grabs all form inputs, encrypts, sends via ajax then updates DOM on response
    grab(elementID, controller):
    elementID div element that has all it's contents rewritten
    controller all form inputs are processed & app requires it
*/
// export 
function grab(elementID, m, c, form_id) {

    //Grabs all form inputs
    let inputsArray = {};
    // console.log(form_id);

    if (form_id > '') {

        let form = document.getElementById(form_id);
        let inputs = form.getElementsByTagName("input");
        let selects = form.getElementsByTagName("select");
        let textareas = form.getElementsByTagName("textarea");


        let formchk = 0;
        let formreq = 0;

        if (inputs.length > 0) {
            for (var x = 0; x < inputs.length; x++) {
                formreq += (inputs[x].required) ? 1 : 0;
                formchk += (myvalidate(inputs[x])) ? 1 : 0;
                // console.log("Is element required: " + formreq);
                // console.log("After myvalidate(element): " + formchk);

                switch (inputs[x].type) {
                    case 'text':
                    case 'password':
                    case 'hidden':
                    case 'email':
                        inputsArray[strings.replace(inputs[x].name)] = strings.replace(inputs[x].value);

                        break;
                    case 'radio':
                    case 'checkbox':
                        (inputs[x].checked == true) ?
                            inputsArray[strings.replace(inputs[x].name)] = strings.replace(inputs[x].value) :
                            inputsArray[strings.replace(inputs[x].name)] = strings.replace('off');

                        break;
                    case 'file':
                        if (inputs[x].files.length > 0) {
                            inputsArray[strings.replace(inputs[x].name)] = {
                                'content': fileLoader.result,
                                'name': fileLoader.file.name,
                                'type': fileLoader.file.type,
                                'size': fileLoader.file.size,
                                'width': fileLoader.imgEl.naturalWidth,
                                'height': fileLoader.imgEl.naturalHeight
                            };
                        }
                        break;
                }
            }

        }
        // console.log("After inputs [formreq]: " + formreq);
        // console.log("After inputs [formchk]: " + formchk);


        if (selects.length > 0) {
            for (var x = 0; x < selects.length; x++) {
                formreq += (selects[x].required) ? 1 : 0;
                formchk += (myvalidate(selects[x])) ? 1 : 0;
                // console.log("Is element required: " + formreq);
                // console.log("After myvalidate(element): " + formchk);

                for (var y = 0; y < selects[x].length; y++) {
                    if (selects[x][y].selected == true) {
                        inputsArray[strings.replace(selects[x].name)] = strings.replace(selects[x][y].value);
                    }
                }
            }
        }
        // console.log("After selects [formreq]: " + formreq);
        // console.log("After selects [formchk]: " + formchk);

        // console.log(textareas.length);
        if (textareas.length > 0) {
            for (var x = 0; x < textareas.length; x++) {
                formreq += (textareas[x].required) ? 1 : 0;
                formchk += (myvalidate(textareas[x])) ? 1 : 0;
                // console.log("Is element required: " + formreq);
                // console.log("After myvalidate(element): " + formchk);

                inputsArray[strings.replace(textareas[x].name)] = strings.replace(textareas[x].value);
            }
        }
        // console.log("After textareas [formreq]: " + formreq);
        // console.log("After textareas [formchk]: " + formchk);
        // console.log(inputsArray);

        let popi = document.getElementById('popi_agreement');

        // if (0 === 1) {
        if (formreq > formchk) {
            // console.log("All the required fields need to be completed correctly!");
            (popi !== null) ? scroller.bottom('popi-scroller') : '';
        } else {
            if (popi == null) {
                // console.log('Not a POPI form requirement: ' + inputsArray);
                send(elementID, m, c, inputsArray);
                // console.log(elementID + "," + m + "," + c + "," + inputsArray);
            } else {
                if (popi.checked == true) {
                    // console.log('POPI form requirement is agreed to: ' + inputsArray);
                    send(elementID, m, c, inputsArray);
                    // console.log(elementID + "," + m + "," + c + "," + inputsArray);
                } else {
                    scroller.bottom('popi-scroller');
                    // console.log('POPI form requirement: ' + inputsArray);
                    // console.log("You cannot use this system without giving the software permssion to use your information.");
                }
            }
        }
    }
}

function myvalidate(el) {

    // console.log('NodeName: ' + el.nodeName.toLowerCase() + ' Element type: ' + el.type);
    // console.log('Element Name: ' + el.name);
    // console.log('Is element required: ' + el.required);
    el.classList.remove('is-valid');
    el.classList.remove('is-invalid');

    let result;
    if (el.required) {
        if (el.nodeName.toLowerCase() == 'input') {
            // console.log('input ' + el.value.length + ' type ' + el.type);
            switch (el.type) {
                case 'text':
                    if (el.value.length >= el.minLength) {
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
                case 'password':
                    if (el.value.length >= el.minLength) {
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
                case 'email':
                    if (el.value.length >= el.minLength) {
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
                case 'hidden':
                    if (el.value.length >= el.minLength) {
                        // console.log('hidden: ' + el.name);
                        // console.log(el.value.length >= el.minLength);
                        // console.log(el.value.length);
                        // console.log(el.minLength);
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    if (el.checked) {
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
                case 'file':
                    if (el.files.length > 0) {
                        el.classList.add('is-valid');
                        result = true;
                    } else {
                        el.classList.add('is-invalid');
                        result = false;
                    }
                    break;
            }
        }
        if (el.nodeName.toLowerCase() == 'select') {
            let chk = 0;
            // console.log('chk before test: ' + chk);

            for (var y = 1; y < el.length; y++) {
                if ((el[y].selected == true) && el[y].value > '') {
                    // console.log('select selected ' + y + ' value ' + el[y].value);
                    chk += 1;
                }
            }

            // console.log('chk after test: ' + chk);
            if (chk > 0) {
                el.classList.add('is-valid');
                // console.log('select is-valid');
                result = true;
            }
            if (chk < 1) {
                el.classList.add('is-invalid');
                // console.log('select is-invalid');
                result = false;
            }
            // console.log("Result: " + result);
        }
        if (el.nodeName.toLowerCase() == 'textarea') {
            if (el.minLength <= el.value.length) {
                el.classList.add('is-valid');
                // console.log('textarea is-valid');
                result = true;
            } else {
                el.classList.add('is-invalid');
                // console.log('textarea is-invalid');
                // console.log('textarea needs a minimum of :' + el.minLength + ' characters');
                result = false;
            }
        }
    } else {
        el.classList.add('is-valid');
        result = false;
    }

    return result;
}
