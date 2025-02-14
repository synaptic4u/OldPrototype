<?php if (isset($roleid['value']) && (int) $roleid['value'] > 0) { ?>
<span class="p-1 alert sm alert-success text-center fading"><?php echo $role; ?> was successfully created.</span>
<?php } if(isset($roleid['value']) && (int) $roleid['value'] === 0) { ?>
<span class="p-1 alert sm alert-warning text-center fading"><?php echo $role; ?> was NOT created.</span>
<?php } ?>