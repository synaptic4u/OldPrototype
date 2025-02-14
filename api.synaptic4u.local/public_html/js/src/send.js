// export 
function send(elementID, m, c, inputsArray = {}, append = 0) {
    //  Sets the logout timer
    clearTimeout(timer); 
    logout(1530000);
    
    const val = getHTML("val");
    const session = getHTML("session");
    const sPublicKey = new Uint8Array((getHTML('serverPublicKey')).split(","));

    // Spinner 
    if(append === 0){
        loadSpinner(elementID);
    }

    // Smooth scrolling to the top of window
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });
            
    //	Encryption Build Up - TweetNACL
    const newNonce = () => nacl.randomBytes(nacl.box.nonceLength);

    const generateKeyPair = () => nacl.box.keyPair();

    const encrypt = (secretOrSharedKey, json, key) => {

        const nonce = newNonce();
        const messageUint8 = nacl.util.decodeUTF8(JSON.stringify(json));

        const encrypted = key ?
            nacl.box(messageUint8, nonce, key, secretOrSharedKey) :
            nacl.box.after(messageUint8, nonce, secretOrSharedKey);

        const fullMessage = new Uint8Array(nonce.length + encrypted.length);
        fullMessage.set(nonce);
        fullMessage.set(encrypted, nonce.length);

        const base64FullMessage = nacl.util.encodeBase64(fullMessage);
        return base64FullMessage;
    };

    const decrypt = (secretOrSharedKey, messageWithNonce, key) => {

        const messageWithNonceAsUint8Array = nacl.util.decodeBase64(messageWithNonce);
        const nonce = messageWithNonceAsUint8Array.slice(0, nacl.box.nonceLength);
        const message = messageWithNonceAsUint8Array.slice(
            nacl.box.nonceLength,
            messageWithNonce.length
        );

        const decrypted = key ?
            nacl.box.open(message, nonce, key, secretOrSharedKey) :
            nacl.box.open.after(message, nonce, secretOrSharedKey);

        if (!decrypted) {

            throw new Error('Could not decrypt message');
        }

        // console.log('decrypted');
        // console.log(decrypted);
        const base64DecryptedMessage = nacl.util.encodeUTF8(decrypted);
        return JSON.parse(base64DecryptedMessage);
    };

    //	Toggle hamburger closed
    // navTogglerCheck();

    //	Removes dynamic script tag and content
    wipe.script();

    console.log('append: ' + append);
    console.log('elementID: ' + elementID);
    console.log('m: ' + m);
    console.log('c: ' + c);
    console.log('inputsArray: ' + inputsArray);

    let xhttp = null;

    // console.log('sPublicKey');
    // console.log(sPublicKey);

    const client = generateKeyPair();
    // console.log('client.publicKey: ');
    // console.log(client.publicKey);
    // console.log('client.secretKey: ');
    // console.log(client.secretKey);

    const clientShared = nacl.box.before(sPublicKey, client.secretKey);
    // console.log('clientShared');
    // console.log(clientShared);

    const cipher = encrypt(clientShared, JSON.stringify(inputsArray));
    // console.log('cipher: ' + cipher);

    let data = new FormData();
    data.append('k', session);
    data.append('s', cipher);
    data.append('cpk', client.publicKey);
    data.append('i', val);
    data.append('c', c);
    data.append('m', m);

    for (var pair of data.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    (window.XMLHttpRequest) ? xhttp = new XMLHttpRequest() : xhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            if (document.getElementById("init")) {
                setStyle("init", "display: block;");
            }

            console.log('readyState: ' + this.readyState + 'status: ' + this.status);
            console.log("In readystate this.responseText: " + this.responseText);
            console.log(typeof this.responseText);
            let reply = JSON.parse(this.responseText);

            console.log(reply);

            //	HTML
            let cipher_html = reply.html;
            console.log('cipher_html: ' + cipher_html);
            const message_html = decrypt(clientShared, cipher_html);
            console.log('message_html: ' + message_html);

            if(append === 1){
                appendHTML(elementID, strings.return(message_html))
            }else{
                setHTML(elementID, strings.return(message_html));
            }

            //	System Params
            let cipher_system_params = reply.system_params;
            // console.log('cipher_system_params: ' + cipher_system_params);
            const message_system_params = decrypt(clientShared, cipher_system_params);
            // console.log('message_system_params: ' + message_system_params);

            if (document.getElementById("system_params")) {
                setHTML('system_params', message_system_params);
            }

            //	Script
            let cipher_script = reply.script;
            // console.log('cipher_script: ' + cipher_script);
            const message_script = decrypt(clientShared, cipher_script);
            console.log('message_script: ' + message_script);

            if (message_script != '') {
                let dynamic = document.createElement("script");
                dynamic.setAttribute("id", "dynamic");
                dynamic.setAttribute("type", "text/javascript");

                let calls = document.createTextNode(message_script.replace(/\n/g, " "));
                dynamic.appendChild(calls);
                document.body.appendChild(dynamic);
            }

            console.log(elementID);

            // console.log("send() - finished");
        }
    };

    // console.log('Before send');
    xhttp.open("POST", "https://"+url_server+"/base.php?", true);
    // xhttp.withCredentials = true;
    // xhttp.setRequestHeader('Content-Type', 'text/html');
    // xhttp.setRequestHeader('Content-Type', 'application/json');
    xhttp.send(data);
    // console.log('After send');
}