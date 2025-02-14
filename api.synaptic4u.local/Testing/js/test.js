
function post(path, params, method='post') {

    // The rest of this code assumes you are not using a library.
    // It can be made less verbose if you use one.
    const form = document.createElement('form');
    form.method = method;
    form.action = path;
  
    for (const key in params) {
      if (params.hasOwnProperty(key)) {
        const hiddenField = document.createElement('input');
        hiddenField.type = 'text';
        hiddenField.name = key;
        hiddenField.value = params[key];
  
        form.appendChild(hiddenField);
      }
    }
  
    document.body.appendChild(form);
    form.submit();
  
}

const strings = {

    'replace': (string) => {
        // Replaces app unfriendly characters

        let strComma = string.replace(/,/g, '__:comma:__');
        let strSingleQuotes = strComma.replace(/'/g, '__:sngquote:__');
        let strDoubleQuotes = strSingleQuotes.replace(/"/g, '__:dblquote:__');
        let strOBrackets = strDoubleQuotes.replace(/</g, '__:openbracket:__');
        let strCBrackets = strOBrackets.replace(/>/g, '__:closebracket:__');
        let strReturn = strCBrackets.replace(/\r/g, '__:return:__');
        let strOnload = strReturn.replace(/\n/g, '__:newline:__');
        let strNew = strOnload.replace(/onload=/g, '__:onloadequals:__');
        // strNew = string;
        return strNew.replace(/\+/g, '__plus__').replace(/\//g, '__slash__').replace(/\=/g, '__equals__');
    },

    'return': (string) => {
        // Restores app unfriendly characters

        let strComma = string.replace(/__:comma:__/g, ',');
        let strSingleQuotes = strComma.replace(/__:sngquote:__/g, '\'');
        let strDoubleQuotes = strSingleQuotes.replace(/__:dblquote:__/g, '"');
        let strOBrackets = strDoubleQuotes.replace(/__:openbracket:__/g, '<');
        let strCBrackets = strOBrackets.replace(/__:closebracket:__/g, '>');
        let strReturn = strCBrackets.replace(/__:return:__/g, '\r');
        let strOnload = strReturn.replace(/__:newline:__/g, '\n');
        let strNew = strOnload.replace(/__:onloadequals:__/g, 'onload=');
        // strNew = string;
        return strNew.replace(/__plus__/g,'\+').replace(/__slash__/g,'\/').replace(/__equals__/g,'\=');
    }
};


let params = {
    k : "453f689d2af78d2f49046e99f9fe95988964daa9e894f61f832dc2f2c664e2cd3667ba323a2eebad853970ddba91b02ebc7aecb303402459cf9be2560d2a93d3e1755bd",
    s : strings.replace("WU+hgG3aZOpUlt4sBY+iRVb9X9fMxBdVsi\/Zo6Q6+0JutlDN66RARghCyPkJ9S98hLFTL3j3NEti2dXTXGO6XXgtZ0Zcq0QrGlJeWyp\/P9MbYCPfzkaJaMz1WRAAcrgYLme5w43SwqmjYTr1JUaOE4EnI74\/sw=="),
    cpk : "118,19,40,142,243,185,62,179,148,188,189,56,107,184,243,182,201,44,102,46,8,95,20,45,193,98,254,10,62,133,72,56",
    i : "453f741ae991308a4fc90e0d39520e1e9bf81c8029e8e61f832dcdd4d5fedb97d6f3235eb5121e56385c1b19d36759a8a7e5b2db93498142e424f1e8baa497800ca39",
    c : "453f8f79a2222c311aaf3748e792896911891d1ff255b61f832dc4a8b9457f887984ebad4f37612884d00e79730e70c47483d0c22f4424178dedc4ad335acd45f1fd315223dedab",
    m : "453f9bfbaf8b2d1cdf92bf83857fe1748c0f68de03d4761f832dc65e3be47933f4f78c9f3db2b612293a5982b4b9703df20b7a5bef7a00ef23dbf7e317a48051759d12b"
}

post('https://daemon.synaptic4u.co.za/fun/test.php', params);




let params = {
    k : "453f689d2af78d2f49046e99f9fe95988964daa9e894f61f832dc2f2c664e2cd3667ba323a2eebad853970ddba91b02ebc7aecb303402459cf9be2560d2a93d3e1755bd",
    s : Base64EncodeUrl("WU+hgG3aZOpUlt4sBY+iRVb9X9fMxBdVsi\/Zo6Q6+0JutlDN66RARghCyPkJ9S98hLFTL3j3NEti2dXTXGO6XXgtZ0Zcq0QrGlJeWyp\/P9MbYCPfzkaJaMz1WRAAcrgYLme5w43SwqmjYTr1JUaOE4EnI74\/sw=="),
    cpk : "118,19,40,142,243,185,62,179,148,188,189,56,107,184,243,182,201,44,102,46,8,95,20,45,193,98,254,10,62,133,72,56",
    i : "453f741ae991308a4fc90e0d39520e1e9bf81c8029e8e61f832dcdd4d5fedb97d6f3235eb5121e56385c1b19d36759a8a7e5b2db93498142e424f1e8baa497800ca39",
    c : "453f8f79a2222c311aaf3748e792896911891d1ff255b61f832dc4a8b9457f887984ebad4f37612884d00e79730e70c47483d0c22f4424178dedc4ad335acd45f1fd315223dedab",
    m : "453f9bfbaf8b2d1cdf92bf83857fe1748c0f68de03d4761f832dc65e3be47933f4f78c9f3db2b612293a5982b4b9703df20b7a5bef7a00ef23dbf7e317a48051759d12b"
}



let params = {
    k : "453f689d2af78d2f49046e99f9fe95988964daa9e894f61f832dc2f2c664e2cd3667ba323a2eebad853970ddba91b02ebc7aecb303402459cf9be2560d2a93d3e1755bd",
    s : "453f689d2af78d2f49046e99f9fe95988964daa9e894f61f832dc2f2c664e2cd3667ba323a2eebad853970ddba91b02ebc7aecb303402459cf9be2560d2a93d3e1755bd",
    cpk : "118,19,40,142,243,185,62,179,148,188,189,56,107,184,243,182,201,44,102,46,8,95,20,45,193,98,254,10,62,133,72,56",
    i : "453f741ae991308a4fc90e0d39520e1e9bf81c8029e8e61f832dcdd4d5fedb97d6f3235eb5121e56385c1b19d36759a8a7e5b2db93498142e424f1e8baa497800ca39",
    c : "453f8f79a2222c311aaf3748e792896911891d1ff255b61f832dc4a8b9457f887984ebad4f37612884d00e79730e70c47483d0c22f4424178dedc4ad335acd45f1fd315223dedab",
    m : "453f9bfbaf8b2d1cdf92bf83857fe1748c0f68de03d4761f832dc65e3be47933f4f78c9f3db2b612293a5982b4b9703df20b7a5bef7a00ef23dbf7e317a48051759d12b"
}