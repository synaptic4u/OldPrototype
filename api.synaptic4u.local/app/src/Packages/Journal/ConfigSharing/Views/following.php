<form method="POST" class="container-fluid fading" id="ConfigShares" role="form">

    <div class="container-flex mt-2">

        <div class="row">

            <div class="col-12 text-center">

                <h5 class="h5">Users that you follow</h5>
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

                <span class="h6 mr-1">Remove</span>
            </div>
        </div>

        <?php foreach ($data as $section) { ?>

        <div class="form-group row rounded border mb-1 py-1">

            <div class="col-5 mt-2 mb-1 text-left px-0 ps-1">

                <span class=" pe-0"><?php echo $section[1]; ?> <?php echo $section[2]; ?></span>
            </div>

            <div class="col-3 mt-2 mb-1 text-left px-0">

                <span class="ms-1 pe-0"><?php echo substr($section[3], 2, 8); ?></span>
            </div>

            <div class="col-4 mt-1 mb-1 text-right">

                <a class="btn btn-outline-secondary btn-sm"
                    onclick="send('config_following','<?php echo $calls['unfollow']; ?>','<?php echo $calls['ConfigSharing1']; ?>', ['<?php echo $section[0]; ?>']);">
                    <span class="">Unfollow</span>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</form>