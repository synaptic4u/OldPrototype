<?php if (!is_null($rowcount) && (int)$rowcount > 0) { ?>
    <span class="p-1 alert sm alert-success text-center fading"><?php echo $type['value']; ?> was successfully updated.</span>
<?php } else { ?>
    <span class="p-1 alert sm alert-warning text-center fading"><?php echo $type['value']; ?> was NOT updated.</span>
<?php } if (!is_null($count) && (int) $count > 0) { ?>
    <span class="p-1 alert sm alert-warning text-center fading">The following are dependant upon this type:
    <?php foreach($orgs as $val){ ?>
        <br><?php echo $val['name']; ?>, 
    <?php } ?>Please update them.</span>
<?php } ?>