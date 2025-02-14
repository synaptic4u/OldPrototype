<?php if ($this->data['sectionid'] > 0) { ?>
<span class="p-1 alert sm alert-success text-center fading">Your section title was created on <?php echo $datedon; ?></span>
<?php } ?>
<?php if (-1 === $this->data['sectionid']) { ?>
<span class="p-1 alert sm alert-warning text-center fading">Your section title limit is full<br>Your section title was NOT created</span>
<?php } ?>