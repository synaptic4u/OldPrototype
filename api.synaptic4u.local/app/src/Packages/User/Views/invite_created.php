<div class="row mt-4 fading">
    <div class="col d-flex justify-content-center">
        <?php 
            if ((int)$userid > 0) {
        ?>
           <?php echo $name; ?> was successfully activated.
        <?php
            } else {
        ?>
           <?php echo $name; ?> was NOT activated.
        <?php
            }
        ?>
    </div>
</div>