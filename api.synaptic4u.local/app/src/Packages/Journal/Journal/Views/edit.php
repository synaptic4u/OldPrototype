<div class="container-flex mt-1 fading">	

    <form method="POST" action="" id="Journal">

        <div class="row">

            <div class="col-12 text-center">
    
                <h5 class="h5 mt-1 mb-0">Journal entry: <?php echo $details['datedon']; ?></h5>  

                <input type="hidden" value="<?php echo $details['userid']; ?>" />
                <input type="hidden" value="<?php echo $details['journalid']; ?>" />
            </div>
        </div>

        <?php foreach ($journal as $title => $content) { ?>

        <div class="row mt-1">
            
            <div class="col-sm-12">

                <fieldset class="border rounded pl-3 pr-3 pb-3 pt-1">

                    <legend class="w-auto"><span class="pl-2 pr-2 h6">

                        <?php echo $title; ?></span>
                    </legend>
                    
                    <textarea class="form-control border rounded" rows="4"><?php echo $content; ?></textarea>
                </fieldset>
            </div>
        </div>
        <?php } ?>

        <div class="row mt-1 mb-4">
            <div class="col col-lg-12 d-flex justify-content-center">
            
                <button
                    class="btn btn-sm btn-outline-primary" 
                    type="button" 
                    onclick="grab('main_container','<?php echo $calls['update']; ?>','<?php echo $calls['Journal']; ?>', this.form.id);"
                >Save</button>
            </div>
        </div>
    </form>
</div>