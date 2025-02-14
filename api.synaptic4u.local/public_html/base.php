<?php

    if (file_exists(dirname(__FILE__, 2).'/vendor/autoload.php')) {
        require_once dirname(__FILE__, 2).'/vendor/autoload.php';
        // var_dump(dirname(__FILE__, 2).'/vendor/autoload.php');
    }

    use Synaptic4U\Core\App;
    use Synaptic4U\Core\Log;

    // declare(strict_types=1);

    new Log([
        'Location' => 'api.synaptic4u.co.za - base.php - FLOW DIAGRAM',
    ], 'activity', 3);

    // include_once '../app/src/core/App.php';

    // include_once '../app/src/core/Log.php';

    // echo json_encode($app);

    // header('Content-Type: application/json');

    // header('Access-Control-Allow-Origin: *');

    try {
        // new Log([
        //     'Location' => 'api.synaptic4u.co.za - base.php',
        //     'Autoload' => dirname(__FILE__, 2).'/vendor/autoload.php',
        //     'POST' => json_encode($_POST, JSON_PRETTY_PRINT),
        // ], 'activity', 3);

        $app = new App();
    } catch (Exception $e) {
        new Log([
            'Location' => 'api.synaptic4u.co.za - base.php',
            'Exception' => $e->__toString(),
        ], 'activity', 3);
    }

    // echo json_encode($_POST);

    // if (true === isset($_SERVER['HTTP_ORIGIN'])) {
    //     $origin = $_SERVER['HTTP_ORIGIN'];

    //     $allowed_origins = [
    //         'https://app.local.synaptic4u.co.za',
    //         'http://app.local.synaptic4u.co.za',
    //     ];

    //     if (true === in_array($origin, $allowed_origins, true)) {
    //         header('Access-Control-Allow-Origin: '.$origin);
    //         header('Access-Control-Allow-Credentials: true');
    //         header('Access-Control-Allow-Methods: POST');
    //         header('Access-Control-Allow-Headers: Content-Type');

    //         $app = new App();

    //         $app = null;
    //     }
    //     if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
    //         exit; // OPTIONS request wants only the policy, we can stop here
    //     }

    // }
