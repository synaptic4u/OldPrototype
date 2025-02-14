<?php

    if (file_exists(dirname(__FILE__, 2).'/vendor/autoload.php')) {
        require_once dirname(__FILE__, 2).'/vendor/autoload.php';
        // var_dump(dirname(__FILE__, 2).'/vendor/autoload.php');
    }

    use Synaptic4U\Core\Encryption;
    use Synaptic4U\Core\EncryptionOLD;

    print_r('Starting tests: OLD <-> NEW'.PHP_EOL);
    print_r('Test Cycles are at a 100000 reps.'.PHP_EOL);

    // CREATE OLD
    $start = microtime(true);
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $encrypt_old = new EncryptionOLD();
        // print_r(json_encode($res).PHP_EOL);
        if (0 === $cnt % 10000) {
            print_r('old_create'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['old_create'] = 'Old ~ Duration sec.microseconds: '.$finish - $start;

    // CREATE NEW
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $encrypt = new Encryption();
        // print_r(json_encode($res).PHP_EOL);
        if (0 === 'Encryption'.$cnt % 10000) {
            print_r('new_create'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['new_create'] = 'New ~ Duration sec.microseconds: '.$finish - $start;

    // sendPublicKeyPair
    // sendPublicKeyPair 100000 REPS OLD
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $res = $encrypt_old->sendPublicKeyPair(7);
        // print_r(json_encode($res).PHP_EOL);
        if (0 === $cnt % 10000) {
            print_r('old_sendPublicKeyPair'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['old_sendPublicKeyPair'] = 'OLD: sec.microseconds: '.$finish - $start;

    // sendPublicKeyPair 100000 REPS NEW
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $res = $encrypt->sendPublicKeyPair();
        // print_r(json_encode($res).PHP_EOL);
        if (0 === $cnt % 10000) {
            print_r('new_sendPublicKeyPair'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['new_sendPublicKeyPair'] = 'NEW: sec.microseconds: '.$finish - $start;

    // hashString
    // hashString 100000 REPS OLD
    // $start = microtime(true);
    // for ($cnt = 0; $cnt < 100000; ++$cnt) {
    //     $res = $encrypt_old->hashString('canteloupe');
    //     // print_r(json_encode($res).PHP_EOL);
    //     if($cnt % 10000 === 0){
    //         print_r($cnt.PHP_EOL);
    //     }
    // }
    // $finish = microtime(true);
    // $result['old_hashString'] = 'OLD: sec.microseconds: '.$finish - $start;

    // hashString 100000 REPS NEW
    // $start = microtime(true);
    // for ($cnt = 0; $cnt < 100000; ++$cnt) {
    //     $res = $encrypt->hashString('canteloupe');
    //     // print_r(json_encode($res).PHP_EOL);
    //     if($cnt % 10000 === 0){
    //         print_r($cnt.PHP_EOL);
    //     }
    // }
    // $finish = microtime(true);
    // $result['new_hashString'] = 'NEW: sec.microseconds: '.$finish - $start;

    // SecretKey
    // SecretKey 100000 REPS OLD
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $res = $encrypt_old->scramble(1234, 'canteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupe');
        // print_r(json_encode($res).PHP_EOL);
        if (0 === $cnt % 10000) {
            print_r('old_SecretKey'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['old_SecretKey'] = 'OLD: sec.microseconds: '.$finish - $start;

    // SecretKey 100000 REPS NEW
    $start = microtime(true);
    for ($cnt = 0; $cnt < 100000; ++$cnt) {
        $res = $encrypt->scramble(1234, 'canteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupecanteloupe');
        // print_r(json_encode($res).PHP_EOL);
        if (0 === $cnt % 10000) {
            print_r('new_SecretKey'.$cnt.PHP_EOL);
        }
    }
    $finish = microtime(true);
    $result['new_SecretKey'] = 'NEW: sec.microseconds: '.$finish - $start;

    // result
    print_r(PHP_EOL.json_encode($result, JSON_PRETTY_PRINT));
