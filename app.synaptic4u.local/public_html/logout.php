<?php

include_once dirname(__FILE__, 2).'/app/src/core/Log.php';

$logo = (glob('images/logo/*.*'))[0];

$dir = dirname(__FILE__, 2);

$file = '/config.json';

$filepath = $dir.$file;

$config = json_decode(file_get_contents($filepath), true);

new Log([
    'Location' => $config['url']['client'].' - logout.php',
    '$_GET' => $_GET,
]);
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

    <!-- STYLESHEETS : INTERNAL -->
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://<?php echo $config['url']['client']; ?>/css/app.css">

</head>

<body id="body">

    <!-- MAIN APP CONTAINER with NAV - Dynamic -->
    <div class="container mt-3" id="init" style="display: block;">

        <div class="row">
            <div class="col d-flex justify-content-center">
                <img loading="lazy" class="image rounded" src="<?php echo $logo; ?>" width="230rem" height="200rem" />
            </div>
        </div>

        <div class="row mt-5">

            <div class="col d-flex justify-content-center">

                <?php

                    if (isset($_GET['message']) && strlen($_GET['message']) > 1) {
                        echo '<span class="alert alert-info text-center">'.$_GET['message'].'</span>';
                    }
?>
            </div>
        </div>
    </div>

    <!-- SCRIPTS : INTERNAL -->

    <!-- MAIN JAVASCRIPT -->
    <script type="text/javascript">
    setTimeout(function() {
        window.location.href = 'index.php'
    }, 4000);
    </script>

</body>

</html>