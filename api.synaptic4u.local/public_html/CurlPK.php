<?php

    if (file_exists('/var/www/api.synaptic4u.local/vendor/autoload.php')) {
        require_once '/var/www/api.synaptic4u.local/vendor/autoload.php';
    }

    // use Exception;
    use Synaptic4U\Core\Encryption;
    use Synaptic4U\Core\Log;

    // new Log([
    //     'Location' => 'CurlPK.php :  FLOW DIAGRAM',
    // ], 'activity');

    try {
        $curlResponse = null;
        $encrypt = null;
        $keyArray = null;

        // Encryption
        $encrypt = new Encryption();

        $keyArray = $encrypt->sendPublicKeyPair();

        new Log([
            'Location' => 'CurlPK.php : 1',
            '$keyArray' => json_encode($_GET, JSON_PRETTY_PRINT),
        ], 'activity');

        $keyArray['keyid'] = $encrypt->scramble($keyArray['keyid'], 'keyid');

        $curlResponse['s'] = $keyArray['serverPublicKey'];
        $curlResponse['y'] = $keyArray['keyid'];
        $curlResponse['n'] = $encrypt->scramble(-100, 'userid');
        $curlResponse['a'] = $encrypt->scramble('User\User', 'controller');
        $curlResponse['p'] = $encrypt->scramble('login', 'method');
        $curlResponse['t'] = $encrypt->scramble('create', 'method');
        $curlResponse['i'] = $encrypt->scramble('resend', 'method');
        $curlResponse['c'] = $encrypt->hashStringVerify($_GET['key'], 'Одно приложение, чтобы управлять ими и приносить их и в темноте связывать их.');
        // PLEASE SUPPORT UKRAINE
        new Log([
            'Location' => 'CurlPK.php : 2',
            '$curlResponse[s]serverPublicKey' => $curlResponse['s'],
            '$curlResponse[y]keyid' => $curlResponse['y'],
            '$curlResponse[n]userid' => $curlResponse['n'],
            '$curlResponse[a]controller' => $curlResponse['a'],
            '$curlResponse[p]login' => $curlResponse['p'],
            '$curlResponse[t]create' => $curlResponse['t'],
            '$curlResponse[i]resend' => $curlResponse['i'],
            '$curlResponse[c]confirmation' => $curlResponse['c'],
            'GET key' => $_GET['key'],
        ], 'activity');

        $encrypt = null;
        $keyArray = null;
    } catch (Exception $e) {
        $curlResponse['error'] = $e->__toString();

        new Log([
            'Location' => 'CurlPK.php',
            'Error' => $e->__toString(),
        ], 'error');
    } finally {
        $encrypt = null;
        $keyArray = null;

        echo json_encode($curlResponse, JSON_PRETTY_PRINT);
    }
