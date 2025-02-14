
<form method="POST" class="container-fluid px-0 fading" id="ConfigShares" role="form">
            
    <div class="container-flex mt-3">
        
        <div class="row">

            <div class="col-12 text-center">

                <h5 class="h5">Users currently available to follow</h5>   
            </div>
        </div>

        <div class="form-group row mt-2">
            
            <div class="col-12">

                <fieldset class="border rounded pl-3 pr-3 pb-3 pt-1">

                    <legend class="w-auto">
                    
                        <span class="pl-2 pr-2 h6">Select then send your request.</span>
                    </legend>
                    
                    <select
                        class="selectpicker show-tick form-control btn btn-sm btn-outline-secondary" 
                        data-live-search="true"
                    >
                        <option class="btn-sm btn-outline-secondary" value="0">Select the user</option>';
                        
                        <?php foreach ($data as $key => $value) { ?>
                        
                        <option class="btn-sm btn-outline-secondary" value="<?php echo $value[0]; ?>"><?php echo $value[1]; ?> <?php echo $value[2]; ?></option>
                        <?php } ?>

                    </select>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            
            <div class="col-12 text-center">
    
                <button
                    class="btn btn-sm btn-outline-primary" 
                    type="button" 
                    onclick="grab('config_shared','<?php echo $calls['request']; ?>','<?php echo $calls['ConfigSharing1']; ?>', this.form.id);"
                >Send the request</button>
            </div>
        </div>
    </div>
</form>