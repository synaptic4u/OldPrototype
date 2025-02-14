<?php

/**
 * Main index.php file used by API.
 * This will pull the images from a local directory.
 */
include_once dirname(__FILE__, 2).'/app/src/core/Log.php';

// echo dirname(__FILE__, 2).'/app/src/core/Log.php';

function hashStringVerify($hash, $string)
{

    // Returns 1 if true, 0 if false
    try {
        $check = 0;

        //~~ Returns 1 if true, 0 if false
        $hash2 = str_replace('~~~~', '=', $hash);
        $hash3 = base64_decode($hash2);

        $check = sodium_crypto_pwhash_str_verify($hash3, $string);

        // new Log([
        //     'Location' => 'index.php : hashStringVerify',
        //     'string' => $string,
        //     'hash' => $hash,
        //     'hash2' => $hash2,
        //     'hash3' => $hash3,
        //     'check' => $check,
        // ]);
    } catch (Exception $e) {
        // new Log([
        //     'Location' => 'error - index.php : hashStringVerify',
        //     '$e' => $e->__toString(),
        // ]);

        $check = 0;
    } finally {
        return $check;
    }
}

// phpinfo();

try {
    $script = '';
    $params = [];
    $cnt = 0;
    $check = [
        'confirmation',
        'confirm',
        'User'
    ];
    $result = null;

    $id = 'body';
    $disabled = '';

    $logo = (glob('./images/logo/*.*'))[0];

    $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

    $browser = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_SPECIAL_CHARS);

    $mobile = (int) preg_match('/(KFAPWI|android|avantgo|blackberry|bolt|boost|cricket|docomo |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\\.browser|up\\.link|webos|wos)/i', $_SERVER['HTTP_USER_AGENT']);

    $filepath = dirname(__FILE__, 2).'/config.json';

    $config = json_decode(file_get_contents($filepath), true);

    // phpinfo();
    new Log([
        'Location' => $config['url']['client'].' - index.php',
        'IP Address' => $ip,
        'Browser User Agent' => json_encode($browser, JSON_PRETTY_PRINT),
        'Is Mobile' => (1 === (int) $mobile) ? 'Yes' : 'No',
        'config' => json_encode($config, JSON_PRETTY_PRINT),
        'SERVER' => json_encode($_SERVER, JSON_PRETTY_PRINT),
    ]);

    if(isset($config['production']['enabled']) && (int)$config['production']['enabled'] === 1) {

        $ch = curl_init();

        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => 'https://'.$config['url']['server'].'/CurlPK.php?key='.$config['key'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]
        );

        $result = curl_exec($ch);

        // var_dump($result);

        if ((!$result) || (is_null($result))) {
            echo "No result";
            // if curl_exec() returned false and thus failed
            throw new Exception('An error has occurred: '.(strlen(curl_error($ch)) > 0) ? curl_error($ch) : "No result");
        }
        $result = json_decode($result);

        $myserver = $result->c;

        new Log([
            'Location' => $config['url']['client'].' - index.php',
            'result' => json_encode($result, JSON_PRETTY_PRINT),
            'myserver' => $myserver
        ]);

        if((int)$myserver === 0) {
            throw new Exception("Not my server");
        }
    }

    // echo 'Size of $_GET: '.sizeof($_GET);
    if(sizeof($_GET) >= 2) {
        foreach($_GET as $key => $value) {
            if($cnt === 1) {
                switch (true) {
                    case hashStringVerify($key, $check[$cnt]):
                        $params[$cnt] = $value;
                        break;

                    case hashStringVerify($key, 'invite'):
                        // case hashStringVerify($key, 'confirm'):
                        //     case hashStringVerify($key, 'confirmation'):
                        $params[$cnt] = $value;
                        $id = 'init';
                        break;
                }
            } else {
                if(hashStringVerify($key, $check[$cnt])) {
                    $params[$cnt] = $value;
                } else {
                    $params[$cnt] = null;
                }
            }
            $cnt++;
        }

        $script = "send('".$id."', '".$params[1]."','".$params[2]."', ['".$params[0]."']);";

        if(is_null($params[0]) || is_null($params[1]) || is_null($params[2])) {
            $script = "setTimeout(function(){ window.location.href = 'logout.php?message=The link is invalid.<br>Please contact ".$config['admin']['email'].".';},1);";
        }
    }
} catch (Exception $e) {

    $config['production']['enabled'] = 0;

    $_GET['message'] = "There is something faulty with the connection.<br>Try refreshing the page or email the webmaster.<br>email: ".$config['admin']['email']."<br>Thank you.";
    new Log([
        'Location' => $config['url']['client'].' - index.php',
        'Error' => $e->__toString(),
    ]);

} finally {

    $disabled = (isset($config['production']['enabled']) && (int)$config['production']['enabled'] === 1) ? '' : 'disabled';

    // var_dump(curl_error($ch));
    // echo("<br>");
    // var_dump($result);
    // var_dump($logo);
    // var_dump($config);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- META -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- TITLE & ICON -->
    <title>Synaptic4U Systems</title>
    <link rel="shortcut icon" href="<?php echo $logo; ?>" />

    <!-- STYLESHEETS : EXTERNAL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


    <!-- STYLESHEETS : INTERNAL -->
    
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/bootstrap.min.css.map">
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/nav.css">
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/app.css">
    <link rel="stylesheet" href="https://<?php echo $config['url']['server']; ?>/css/canvas.css">
    <link rel="stylesheet" href="https://<?php echo $config['url']['server']; ?>/css/hierachy.css">

</head>

<body id="body" class="">
    <main class="dyn-margin" id="main" style="margin-left:0px;">

        <!-- MAIN APP CONTAINER - Dynamic -->
        <div class="container mb-3" id="init">

            <form class="needs-validation" method="POST" action="" id="User" novalidate>

                <div class="container mt-3">

                    <div class="row">

                        <div class="col d-flex justify-content-center">
                            <img loading="lazy" class="image rounded" src="<?php echo $logo; ?>" width="230rem" height="200rem" />
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col d-flex justify-content-center">

                            <h5 class="h5">Enter in your login details</h5>
                        </div>
                    </div>

                    <div class="row mt-0">

                        <div class="col d-flex justify-content-center">
                            <?php if(isset($config['production']['enabled']) && (int)$config['production']['enabled'] === 1) { ?>
                                    <p class="text-center">Upon submission you will receive a confirmation email to login.</p>
                            <?php } else { ?>
                                    <h5 class="h5">Service available soon!</h5>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row mt-0">

                        <div class="col d-flex justify-content-center">

                            <?php

                            if (isset($_GET['message']) && strlen($_GET['message']) > 1) {
                                echo '<span class="alert alert-danger">'.$_GET['message'].'</span>';
                            }
?>
                        </div>
                    </div>

                    <div class="row mt-0">
                        <div class="col-sm-12 col-md-6 justify-content-center m-auto">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0" id="email-field">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Email
                                        Address</span>
                                </legend>

                                <input minLength="7"
                                    class="w-100 p-1 form-control required <?php echo (!is_null($email['message'])) ? $email['message'] : ''; ?>"
                                    type="email" placeholder="Email Address" aria-describedby="email" name="email"
                                    value="<?php echo (!is_null($email['value'])) ? $email['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 7 characters and a valid domain name is required.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-0">

                        <div class="col-sm-12 col-md-6 justify-content-center m-auto">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Password</span></legend>

                                <input minLength="15"
                                    class="w-100 p-1 form-control required <?php echo (!is_null($password['message'])) ? $password['message'] : ''; ?>"
                                    type="password" placeholder="Password" aria-describedby="passkey" name="passkey"
                                    value="<?php echo (!is_null($password['value'])) ? $password['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 15 character is requireds.<br>
                                    No "<" or ">" characters allowed. </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row pt-3">

                        <div class="col d-flex justify-content-center">

                            <button class="btn btn-sm btn-outline-primary <?php echo $disabled; ?>" type="button"
                                onclick="grab('init','<?php echo $result->p; ?>','<?php echo $result->a; ?>', this.form.id);">Submit</button>
                        </div>
                    </div>

                    <div class="row pt-3 mb-2">

                        <div class="col d-flex justify-content-center">

                            <button class="btn btn-outline-secondary btn-sm me-2 <?php echo $disabled; ?>" type="button"
                                onclick="send('init','<?php echo $result->i; ?>','<?php echo $result->a; ?>');">Forgot
                                Password</button>

                            <button class="btn btn-outline-secondary btn-sm <?php echo $disabled; ?>" type="button"
                                onclick="send('init','<?php echo $result->t; ?>','<?php echo $result->a; ?>');">Register</button>
                        </div>
                    </div>

                  <?php if(isset($config['link']['enabled']) && (int)$config['link']['enabled'] === 1) { ?>
                    <div class="row mt-4">

                        <div class="col d-flex justify-content-center">

                            <a href="<?php echo $config['link']['url']; ?>" target="_blank" class="link-primary text-center"><?php echo $config['link']['text']; ?></a>
                        </div>
                    </div>
                  <?php } ?>
                    
                </div>
            </form>
        </div>

        <!-- SYSTEM PARAMS CONTAINER updated on each call -->
        <div id="system_params" style="display: none;">

            <p id="serverPublicKey"><?php echo implode(',', $result->s); ?>
            </p>
            <p id="session"><?php echo $result->y; ?>
            </p>
            <p id="val"><?php echo $result->n; ?>
            </p>
        </div>
    </main>

    <!-- SCRIPTS : INTERNAL -->
    <script src="https://<?php echo $config['url']['server']; ?>/js/popper.min.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/bootstrap.min.js"></script>
    <!-- <script src="https://<?php echo $config['url']['server']; ?>/js/jquery-3.5.1.js"></script> -->
    <script src="https://<?php echo $config['url']['server']; ?>/js/nacl.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/nacl-util.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/source.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/paginate.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/grab.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/send.js"></script>
    <script id="canvas_js" src="https://<?php echo $config['url']['server']; ?>/js/src/canvas.js"></script>
    <script id="hierachy_js" src="https://<?php echo $config['url']['server']; ?>/js/src/hierachy.js"></script>

    <!-- MAIN JAVASCRIPT -->
    <script type="text/javascript">
        var url_server = "<?php echo $config['url']['server']; ?>";
        var url_client = "<?php echo $config['url']['client']; ?>";
        
        <?php echo $script; ?>
    </script>
</body>

</html>