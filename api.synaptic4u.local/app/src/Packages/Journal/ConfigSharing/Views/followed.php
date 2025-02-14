<form method="POST" class="container-fluid fading" id="ConfigShares" role="form">
                            
    <div class="container-flex mt-2">
        
        <div class="row">

            <div class="col-12 text-center">

                <h5 class="h5">Users that follow you</h5>   
            </div>
        </div>

        <div class="form-group row p-1 mt-2 mb-0">
            
            <div class="col-5 mt-1 text-left px-0">
                    
                <span class="h6 ml-0 pr-0">User</span>
            </div>
                
            <div class="col-3 mt-1 text-left px-0">
                
                <span class="h6 ml-1 pr-0">Since</span>
            </div>
                
            <div class="col-4 mt-1 text-right">
                
                <span class="h6 mr-1">Remove</span>
            </div>
        </div>
        
        <?php foreach ($data as $section) { ?>

        <div class="form-group row rounded border mb-1 py-1">
            
            <div class="col-5 mt-2 mb-1 text-left px-0 pl-1">
                    
                <span class=" pr-0"><?php echo $section[1]; ?> <?php echo $section[2]; ?></span>
            </div>
                
            <div class="col-3 mt-2 mb-1 text-left px-0">
                
                <span class="ml-1 pr-0"><?php echo substr($section[3], 2, 8); ?></span>
            </div>
                
            <div class="col-4 mt-1 mb-1 text-right">
                    
                <a 
                    class="btn btn-outline-secondary btn-sm" 
                    onclick="send('config_followed_by','<?php echo $calls['removeFollow']; ?>','<?php echo $calls['ConfigSharing']; ?>', ['<?php echo $section[0]; ?>']);"
                > 
                    <span class="">Remove</span>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</form>