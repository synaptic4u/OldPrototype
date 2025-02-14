<?php if (isset($hierachytypeid['value']) && (int) $hierachytypeid['value'] > 0) { ?><span
    class="p-1 alert sm alert-success text-center fading"><?php echo $type; ?> was successfully
    created.
</span><?php } if(isset($hierachytypeid['value']) && (int) $hierachytypeid['value'] === 0) { ?><span class="p-1 alert sm alert-warning text-center fading"><?php echo $type; ?>
    was NOT
    created.</span><?php }  if ((isset($hierachytypedefault['value']) && (int) $hierachytypedefault['value'] === 0) && (isset($rowcount) && (int) $rowcount > 0)){ ?><span
    class="p-1 alert sm alert-success text-center fading"> System defaults have been activated.
</span><?php } if (isset($orgs) && sizeof($orgs) > 0){ ?>
    <span class="p-1 alert sm alert-warning text-center fading">The following are dependant upon the custom types:<?php foreach($orgs as $val){ ?>
        <br><?php echo $val['name']; ?>,
<?php } ?> please update them.</span>
<?php } ?>