<div class="container-flex mt-1" id="Journal">	
            
    <div class="row">

        <div class="col-md-2 col-sm-0">
        </div>

        <div class="col-md-8 col-sm-12 text-center">
        
            <h5 class="h5 mt-1 mb-0"><?php echo $heading; ?></h5>   
        </div>

        <?php if (null !== $shared) { ?>

            <div class="col-md-2 col-sm-0">
            </div>
        <?php } else { ?>

        <div class="col-md-2 col-sm-12 pr-md-4 fading">

            <div class="form-row justify-content-md-end justify-content-center align-self-bottom mr-md-2">

                <button
                    class="btn btn-sm btn-outline-secondary" 
                    type="button" 
                    onclick="send('main_container','<?php echo $calls['create']; ?>','<?php echo $calls['Journal']; ?>', ['<?php echo $userid; ?>']);"
                >New Entry</button>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div class="row mt-2">
        
        <div class="col-sm-12">

            <div class="container" id="journal_entries">

            <!-- Pagination content goes here -->

            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        
        <div class="col-sm-12 d-flex justify-content-center">

            <nav aria-label="Journal entries">
                
                <ul class="pagination pg-grey" id="journal_pagination">
                
                    <li class="page-item prev">
                    
                        <a 
                            class="page-link" 
                            aria-label="Previous"
                        >                                        
                            <span aria-hidden="true">&laquo;</span>
                        
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>


<?php for ($x = 1; $x <= $size; ++$x) {?>

                    <li class="page-item">
                        
                        <a 
                            class="page-link" 
                            onclick="send('journal_entries','<?php echo $method; ?>','<?php echo $calls['JournalList']; ?>', ['<?php echo $userid; ?>','<?php echo array_shift($pages); ?>','<?php echo array_shift($pages); ?>']);"
                        ><?php echo $x; ?></a>
                    </li>
<?php } ?>

                    <li class="page-item next">
                                    
                        <a 
                            class="page-link" 
                            aria-label="Next"
                        >
                            <span aria-hidden="true">&raquo;</span>
                    
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>            
</div>