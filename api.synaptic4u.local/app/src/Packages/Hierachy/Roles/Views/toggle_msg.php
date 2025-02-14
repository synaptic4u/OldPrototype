<?php if (!is_null($rowcount) && (int)$rowcount > 0) { ?>
    <span class="p-1 alert sm alert-success text-center fading"><?php echo $role['value']; ?> was successfully updated.</span>
<?php } else { ?>
    <span class="p-1 alert sm alert-warning text-center fading"><?php echo $role['value']; ?> was NOT updated.</span>
<?php } if (!is_null($count) && (int) $count > 0) { ?>
    <span class="p-1 alert sm alert-warning text-center fading">The following are dependant upon this role:
    <?php foreach($orgs as $val){ ?>
        <br>Organization : <?php echo $val['name']; ?> User : <?php echo $val['user']; ?>, 
    <?php } ?>Please update them.</span>
<?php } ?>