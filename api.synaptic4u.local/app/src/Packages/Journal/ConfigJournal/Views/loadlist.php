<div class="container mt-3 mb-3 fading">

    <div class="row">

        <div class="col-md-3 col-sm-0 text-md-left text-center align-self-bottom px-0">
                        
                
        </div>
        <div class="col-md-6 col-sm-12 text-center align-self-bottom pl-md-3">
                
            <h5 class="h5">Section title's for your journal</h5>   
        </div>

        <div class="col-md-3 col-sm-12 pr-md-4">

            <div class="form-row justify-content-md-end justify-content-center align-self-bottom">

                <button
                class="btn btn-sm btn-outline-secondary" 
                type="button" 
                onclick="send('dash_show','<?php echo $calls['create']; ?>','<?php echo $calls['ConfigJournal1']; ?>');"
                >Create</button>
            </div>
        </div>
    </div>
    
    <div class="row">
        
        <div class="col-6 mt-1">
        
            <span class="h6">Section title</span>
        </div>
            
        <div class="col-6 mt-1">
            
            <span class="h6">Created on</span>
        </div>
    </div>

    <?php foreach ($data as $section) { ?>

    <a 
        class="btn btn-outline-secondary text-secondary btn-sm text-left w-100 mt-1" 
        onclick="send('dash_show','<?php echo $calls['show']; ?>','<?php echo $calls['ConfigJournal2']; ?>', ['<?php echo $section['sectionid']; ?>']);"
    > 
        <div class="row">
            
            <div class="col-6">
                
                <span><?php echo $section['title']; ?></span>
            </div>

            <div class="col-6">
                
                <span><?php echo substr($section['datedon'], 2, 14); ?></span>
            </div>
        </div>
    </a>
    <?php } ?>
</div>