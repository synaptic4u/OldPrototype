<div class="container-flex mt-3 mb-3 fading">
                            
    <div class="row">

        <div class="col-12 text-center">

            <h5 class="h5">Users that you follow</h5>   
        </div>

        <div class="col-12 pt-3 text-center">

            <p class="">
            
            <?php if (0 === ((int) $following)) { ?>
                <span class="p-1 alert alert-sm alert-secondary text-center">You are currently not following anyone.</span>
            <?php } else { ?>
                <span class="p-1 alert alert-sm alert-dark text-center">Your request has NOT been processed.<br>Please contact the Admin.</span>
            <?php } ?>
            </p>   
        </div>
    </div>
</div>