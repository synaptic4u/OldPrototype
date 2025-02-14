<div class="container-flex mt-3 mb-3 fading">
                            
    <div class="row">

        <div class="col-12 text-center">

            <h5 class="h5">Users currently available to follow</h5>   
        </div>

        <div class="col-12 pt-3 text-center">

            <p class="">

            <?php if (isset($count) && (0 === ((int) $count))) { ?>
                <span class="p-1 alert alert-sm alert-secondary text-center">There are no shares currently available.</span>
            <?php } else { ?>
                <span class="p-1 alert alert-sm alert-dark text-center">Sharing is presently unavailable.</span>
            <?php } ?>
            </p>   
        </div>
    </div>
</div>