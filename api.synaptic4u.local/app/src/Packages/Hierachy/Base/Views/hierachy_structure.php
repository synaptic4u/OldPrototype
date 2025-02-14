<li class="row" id="" value="">
    <div class="col-4 nav-link hierachy-link justify-content mx-0 px-0">
        <button type="button" class="btn btn-outline-secondary btn-sm mx-2" onclick="">
            <i class="m-0 bi bi-list-ul"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="">
            <i class="m-0 bi bi-list-nested"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="">
            <i class="m-0 bi bi-gear"></i>
        </button>
    </div>
    <a class="nav-link ps-2 hierachy-link ariatoggler col-8" data-bs-toggle="collapse" href="#collapseHierachy"
        role="button" aria-expanded="false" aria-controls="collapseHierachy"
        onclick="manageState(this);showActive(this);">

        <span class="" id="user-profile-name">
            Organization Builder
        </span>
        <span class="right-icon ms-auto">
            <i class="bi bi-chevron-right"></i>
        </span>
    </a>
    <div class="collapse" id="collapseHierachy">
        <div>
            <ul class="navbar-nav ps-3">
                <li>
                    <a id="user-profile-profile" class="nav-link px-3"
                        onclick="showActive(this); send('main_container', '<?php //echo $show;?>', '<?php //echo $user1;?>', ['<?php //echo $userid;?>']);">
                        <span class="me-2">
                            <i class="bi bi-arrow-return-right"></i>
                        </span>
                        <span>
                            Profile
                        </span>
                    </a>
                </li>
                <li>
                    <a id="user-profile-picture" onclick="showActive(this);" class="nav-link px-3">
                        <span class="me-2">
                            <i class="bi bi-arrow-return-right"></i>
                        </span>
                        <span>
                            Update Avatar
                        </span>
                    </a>
                </li>
                <li>
                    <a id="user-profile-logout"
                        onclick="showActive(this); send('body', '<?php //echo $logout;?>', '<?php //echo $user2;?>');"
                        class="nav-link px-3">
                        <span class="me-2">
                            <i class="bi bi-arrow-return-right"></i>
                        </span>
                        <span>
                            Logout
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</li>