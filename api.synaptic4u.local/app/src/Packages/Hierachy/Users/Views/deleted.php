<?php if ((!is_null($users_count) && (int)$users_count > 0) || (!is_null($hierachy_users_count) && (int)$hierachy_users_count > 0)) { ?>
    <span class="p-1 alert sm alert-success text-center fading"><?php echo $user; ?> was successfully deleted from <?php echo $hierachyname; ?>.</span>
<?php } else { ?>
    <span class="p-1 alert sm alert-warning text-center fading"><?php echo $user; ?> was NOT deleted from <?php echo $hierachyname; ?>.</span>
<?php } ?>