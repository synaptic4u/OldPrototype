<div class="container-flex mt-1 fading" >

    <div class="row">
    
        <div class="col-sm-12 col-md-3 pl-lg-0">
        
            <div class="container pl-lg-0 ml-lg-0">

                <div class="col-md-12 d-flex pl-lg-0 ml-lg-0"> 

                    <h5                         
                        class="btn btn-outline-secondary btn-md text-left w-100 mt-1" 
                        onclick="send('dash_show','<?php echo $calls['loadlist']; ?>','<?php echo $calls['ConfigJournal']; ?>');"
                    >Journal Configuration</h5>
                </div>
            
                <div class="col-md-12 d-flex pl-lg-0 ml-lg-0 mt-1">

                    <h5 
                        class="btn btn-outline-secondary btn-md text-left w-100 mt-1" 
                        onclick="send('dash_show','<?php echo $calls['loadRequests']; ?>','<?php echo $calls['ConfigNotifications']; ?>');"
                    >Notifications</h5>
                </div>
        
                <div class="col-md-12 d-flex pl-lg-0 ml-lg-0 mt-1">

                    <h5 
                        class="btn btn-outline-secondary btn-md text-left w-100 mt-1" 
                    >Messages</h5>
                </div>
            
                <div class="col-md-12 d-flex pl-lg-0 ml-lg-0 mt-1">

                    <h5 
                        class="btn btn-outline-secondary btn-md text-left w-100 mt-1"
                        onclick="send('dash_show','<?php echo $calls['loadShareable']; ?>','<?php echo $calls['ConfigSharing']; ?>');" 
                    >Sharing</h5>
                </div>
            </div>
        </div>
    
    
        <div class="col-sm-12 col-md-9 d-flex pl-0 pr-0 pb-3 mb-2 border rounded justify-content-center" id="dash_show">
            
            <div class="d-flex text-center mt-3">
            
                <h3 class="h3 mt-3 mb-3 align-self-center text-secondary">Choose something from your dash!</h3>
            </div>
        </div>
    </div>
</div>
                    