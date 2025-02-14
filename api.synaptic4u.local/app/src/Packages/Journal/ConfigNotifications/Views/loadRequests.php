<div class="container mx-1 px-1 fading">

    <div class="row no-gutters">

        <div id="config_notification_requests" class="col-12 mx-0">

            <form method="POST" class="container-fluid" id="ConfigNotifications" role="form">

                <div class="container-flex mt-2">

                    <div class="row">

                        <div class="col-12 text-center">

                            <h5 class="h5">Users requesting to follow you</h5>
                        </div>
                    </div>

                    <div class="form-group row p-1 mt-2 mb-0">

                        <div class="col-5 mt-1 text-left px-0">

                            <span class="h6 ms-0 pe-0">User</span>
                        </div>

                        <div class="col-3 mt-1 text-left px-0">

                            <span class="h6 ms-1 pe-0">Since</span>
                        </div>

                        <div class="col-4 mt-1 text-right">

                            <span class="h6 mr-1">Accept</span>
                        </div>
                    </div>

                    <?php foreach ($data as $section) { ?>

                    <div class="btn-outline-light text-secondary form-group row rounded border mb-1 py-1">

                        <div class="col-5 mt-2 mb-1 text-left px-0 ps-1">

                            <span class=" pe-0"><?php echo $section[1]; ?> <?php echo $section[2]; ?></span>
                        </div>

                        <div class="col-3 mt-2 mb-1 text-left px-0">

                            <span class="ms-1 pe-0"><?php echo substr($section[3], 2, 8); ?></span>
                        </div>

                        <div class="col-4 mt-1 mb-1 text-right">

                            <a class="btn btn-outline-secondary btn-sm text-dark"
                                onclick="send('config_notification_requests','<?php echo $calls['acceptRequest']; ?>','<?php echo $calls['ConfigNotifications']; ?>', ['<?php echo $section[0]; ?>']);">
                                <span class="">Accept</span>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <div class="row no-gutters">

        <div id="config_notification_" class="col-12 mx-0"></div>
    </div>
    <div class="row no-gutters">

        <div id="config_notification_" class="col-12 mx-0"></div>
    </div>
    <div class="row no-gutters">

        <div id="config_notification_" class="col-12 mx-0"></div>
    </div>
</div>