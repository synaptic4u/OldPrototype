<div class="row mt-2">
    <div class="col-sm-12 d-flex justify-content-center">
        <nav aria-label="UsersPagination">
            <ul class="pagination pg-grey" id="users_pagination">
                <li class="page-item prev">
                    <a class="page-link" aria-label="Previous">                                        
                        <span aria-hidden="true">&laquo;</span>
                        <!-- <span class="sr-only">Previous</span> -->
                    </a>
                </li>

                <?php $x = 1;foreach($usersArray as $list) {?>

                <li class="page-item list" id="users_page_link_anchor_<?php echo $x; ?>">
                    <a 
                        class="page-link" 
                        onclick="send('users_pagination_list','<?php echo $page; ?>','<?php echo $users; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'userids' : '<?php echo $list; ?>'});paginate_set_active.setup('users_page_link_anchor_<?php echo $x; ?>', 'users_pagination');"
                    ><?php echo $x; ?></a>
                </li>
                <?php $x++; } ?>

                <li class="page-item next">
                    <a class="page-link" aria-label="Next">
                        <!-- <span class="sr-only">Next</span> -->
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>            
