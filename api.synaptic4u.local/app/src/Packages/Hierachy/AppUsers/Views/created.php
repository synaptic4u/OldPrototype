<?php if ((int) $rowcount > 0) { ?>
<span class="p-1 alert sm alert-success text-center fading"><?php echo $user; ?> was successfully <?php echo ((int)$include_exclude === 0) ? 'included.' : 'excluded.';?></span>
<?php } else { ?>
<span class="p-1 alert sm alert-warning text-center fading"><?php echo $user; ?> was NOT <?php echo ((int)$include_exclude === 0) ? 'included.' : 'excluded.';?></span>
<?php } ?>   