        <div class="navbar-nav ml-auto nav-flex">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown ">
                    <!-- class="nav-link dropdown-toggle pl-1 after text-decoration-none border-secondary" -->
                    <a class="nav-link dropdown-toggle pl-1 after text-decoration-none text-secondary rounded border-secondary"
                        href="#" id="userDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $data; ?>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right bg-light border-secondary"
                        aria-labelledby="userDropDown">

                        <a class="dropdown-item w-auto text-decoration-none text-secondary m-1 pl-1 btn btn-outline-secondary rounded hover navlist"
                            onclick="showActive(this);send('main_container','<?php echo $show; ?>','<?php echo $user1; ?>');">Profile</a>

                        <a class="dropdown-item w-auto text-decoration-none text-secondary m-1 pl-1 btn btn-outline-secondary rounded hover navlist"
                            style="display: inline-block"
                            onclick="showActive(this);send('init','<?php echo $logout; ?>','<?php echo $user2; ?>');">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        </div>
        </nav>