<div class="container fading">
    <div class="row mt-1">
        <div class="col d-flex justify-content-center">
            <h5 class="h5">Create a new journal entry</h5>
        </div>
    </div>
</div>

<form method="POST" action="" id="Journal" class="fading">

    <div class="container-flex mt-2">
    <?php foreach ($data as $section) { ?>
            
        <div class="form-group">
            
            <fieldset class="border rounded pl-3 pr-3 pb-3 pt-1">

                <legend class="w-auto">
                
                    <span class="pl-2 pr-2 h6"><?php echo $section; ?></span>
                </legend>
                
                <textarea class="form-control border rounded" id="<?php echo $section; ?>" rows="4"></textarea>
            </fieldset>
        </div>
    <?php } ?>
        
        <div class="row mt-2 mb-3">
            <div class="col col-lg-12 d-flex justify-content-center">
            
                <button
                    class="btn btn-sm btn-outline-primary" 
                    type="button" 
                    onclick="grab('main_container','<?php echo $calls['store']; ?>','<?php echo $calls['Journal']; ?>', this.form.id);"
                >Save</button>
            </div>
        </div>
    </div>
</form>