<?php if ((int) $userid === (int) $formArray) {
    $name = '';
} else {
    $name = $details['user'].' for';
} ?>

<div class="container-flex mt-1" id="Journal">	

    <div class="row">

        <div class="col-md-2 col-sm-0">
        </div>

        <div class="col-md-8 col-sm-12 text-center">
        
            <h5 class="h5 mt-1 mb-0">Journal of <?php echo $name.' '.$details['datedon']; ?></h5>   
        </div>

        <?php if ((int) $userid === (int) $formArray) { ?>

        <div class="col-md-2 col-sm-12 pr-md-4 fading">

            <div class="form-row justify-content-md-end justify-content-center align-self-bottom mr-md-2">

                <button
                    class="btn btn-sm btn-outline-secondary" 
                    type="button" 
                    onclick="send('main_container','<?php echo $calls['edit']; ?>','<?php echo $calls['Journal']; ?>', ['<?php echo $details['userid']; ?>', '<?php echo $details['journalid']; ?>']);"
                >Edit</button>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php foreach ($journal as $title => $content) {?>

    <div class="row mt-2">
        
        <div class="col-sm-12">

            <fieldset class="border rounded pl-3 pr-3 pb-3 pt-1">

                <legend class="w-auto">
                
                    <span class="pl-2 pr-2 h6"><?php echo $title; ?></span>
                </legend>
                
                <textarea 
                    class="form-control border rounded" 
                    rows="4"
                    disabled><?php echo $content; ?></textarea>
            </fieldset>
        </div>
    </div>
    <?php }?>
</div>
