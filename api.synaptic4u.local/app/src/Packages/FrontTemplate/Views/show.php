<noscript>
    <!-- No JavaScript enabled Move to index.php -->
    <h1 style="width:300px;margin:0 auto;">JavaScript must be enabled!</h1>
</noscript>

<!-- TOP NAV BAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="topbar" style="display: none;">
    <div class="container-fluid">

        <!-- offcanvas button -->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar" id="sidebar-button" onclick="navTogglerCheck();">
            <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <!-- offcanvas button -->

        <?php if (null === $hierachyname) {?>
        <a class="navbar-brand fw-bold me-auto" onclick="">
            <img src="https://<?php echo $config['url']['server']; ?>/hierachy/default.webp" alt="image of Synaptic4U"
                height="35px" class="me-2 rounded" id="hierachy-profile-img">
            Synaptic4U</a>
        <?php } else { ?>
        <a class="navbar-brand fw-bold me-auto overflow-hidden" onclick="">
            <img src="<?php echo $hierachylogo; ?>"
                alt="image of <?php echo $hierachyname; ?>" height="35px" class="me-2 rounded"
                id="hierachy-profile-img">
            <?php echo $hierachyname; ?>
        </a> 
        <?php } ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
            aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarHeader">
            <form class="d-flex ms-auto">
                <div class="input-group my-3 my-lg-0">
                    <input type="text" class="form-control sm" placeholder="Search..." aria-label="Search"
                        aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary sm" type="button" id="button-addon2">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link">
                        <img src="https://<?php echo $config['url']['server']; ?>/profiles/<?php echo $profile; ?>.webp"
                            alt="image of <?php echo $name; ?>" width="25" height="25" class="rounded-circle"
                            id="user-profile-img">
                        <span class=" d-sm-inline mx-1" id="user-profile-name"><?php echo $name; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- OFFCANVAS NAV SIDEBAR -->
<div class="offcanvas offcanvas-start bg-dark text-white sidebar-nav overflow-auto text-nowrap" tabindex="-1"
    id="sidebar" aria-labelledby="sidebarLabel" style="display: none;">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
        <button type="button" class="btn-close text-white btn-close-display" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
            <ul class="navbar-nav" id="applications_for_navbar">
                <li>
                    <a class="nav-link px-3 sidebar-link ariatoggler" data-bs-toggle="collapse" href="#collapseProfile"
                        role="button" aria-expanded="false" aria-controls="collapseProfile"
                        onclick="manageState(this);showActive(this);">
                        <span class="me-2">
                            <img src="https://<?php echo $config['url']['server']; ?>/profiles/<?php echo $profile; ?>.webp"
                                alt="image of <?php echo $name; ?>" width="25" height="25" class="rounded-circle"
                                id="user-profile-img">
                        </span>
                        <span class="" id="user-profile-name"><?php echo $name; ?></span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-right"></i>
                        </span>

                    </a>
                    <div class="collapse" id="collapseProfile">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a id="user-profile-profile" class="nav-link px-3"
                                        onclick="showActive(this); send('init', '<?php echo $show; ?>', '<?php echo $user1; ?>', ['<?php echo $userid; ?>']);">
                                        <span class="me-2">
                                            <i class="bi bi-person-lines-fill"></i>
                                        </span>
                                        <span>
                                            Profile
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a id="user-profile-picture" onclick="showActive(this);" class="nav-link px-3">
                                        <span class="me-2">
                                            <i class="bi bi-person-circle"></i>
                                        </span>
                                        <span>
                                            Update Avatar
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a id="user-profile-logout"
                                        onclick="showActive(this); send('body', '<?php echo $logout; ?>', '<?php echo $user2; ?>');"
                                        class="nav-link px-3">
                                        <span class="me-2">
                                            <i class="bi bi-person-x-fill"></i>
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
                <li class="px-3 mt-1 mb-2">
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3">
                        System Menu
                    </div>
                </li>
                <li>
                    <a class="nav-link px-3 sidebar-link ariatoggler" data-bs-toggle="collapse" href="#collapseSystem"
                        role="button" aria-expanded="false" aria-controls="collapseSystem"
                        onclick="manageState(this);showActive(this);">
                        <span class=" me-2">
                            <i class="bi bi-tools"></i>
                        </span>
                        <span>
                            System Overview
                        </span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </a>
                    <div class="collapse" id="collapseSystem">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a id="user-profile-logout"
                                        onclick="showActive(this);send('init', '<?php echo $loadDashboard; ?>', '<?php echo $Dashboard; ?>', ['<?php echo $userid; ?>']); "
                                        class="nav-link px-3">
                                        <span class="me-2">
                                            <i class="bi bi-speedometer2"></i>
                                        </span>
                                        <span>
                                            DashBoard
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a id="user-profile-logout"
                                        onclick="showActive(this);send('init', '<?php echo $loadNotifications; ?>', '<?php echo $Notifications; ?>', ['<?php echo $userid; ?>']); "
                                        class="nav-link px-3">
                                        <span class="me-2">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <span>
                                            All Notifications
                                        </span>
                                        <span class="badge bg-secondary">4</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="showActive(this);send('init', '<?php echo $loadSubscriber; ?>', '<?php echo $Subscriber; ?>', ['<?php echo $userid; ?>']);"
                                        class="nav-link px-3 ">
                                        <span class="me-2">
                                            <i class="bi bi-bank"></i>
                                        </span>
                                        <span>
                                            App Subscriber
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link px-3 sidebar-link ariatoggler" data-bs-toggle="collapse"
                                        href="#collapseSettings" role="button" aria-expanded="false"
                                        aria-controls="collapseSettings" onclick="manageState(this);showActive(this);">
                                        <span class="me-2">
                                            <i class="bi bi-gear"></i>
                                        </span>
                                        <span>
                                            Settings
                                        </span>
                                        <span class="right-icon ms-auto">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </a>
                                    <div class="collapse" id="collapseSettings">
                                        <div>
                                            <ul class="navbar-nav ps-3">
                                                <li>
                                                    <a id="user-profile-logout"
                                                        onclick="showActive(this);send('init', '<?php echo $loadHierachy; ?>', '<?php echo $Hierachy; ?>', ['<?php echo $userid; ?>']);"
                                                        class="nav-link px-3">
                                                        <span class="me-2">
                                                            <i class="bi bi-arrow-return-right"></i>
                                                        </span>
                                                        <span>
                                                            Organizational
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a id="user-profile-logout"
                                                        onclick="showActive(this); send('init', '<?php echo $loadBilling; ?>', '<?php echo $Billing; ?>', ['<?php echo $userid; ?>']);"
                                                        class="nav-link px-3">
                                                        <span class="me-2">
                                                            <i class="bi bi-arrow-return-right"></i>
                                                        </span>
                                                        <span>
                                                            Billing
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a onclick="showActive(this);send('init', '<?php echo $loadSupport; ?>', '<?php echo $Support; ?>', ['<?php echo $userid; ?>']);"
                                                        class="nav-link px-3 ">
                                                        <span class="me-2">
                                                            <i class="bi bi-arrow-return-right"></i>
                                                        </span>
                                                        <span>
                                                            Support
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="px-3 mt-1 mb-2">
                    <hr class="dropdown-divider">
                </li>

                <!-- DYNAMIC APPLICATION MENU -->
                <!-- <div id="applications_for_navbar" class="">Applications</div> -->
                
            </ul>
        </nav>
    </div>
</div>

<main class="dyn-margin mt-4 pt-4" id="main" style="margin-top: 65px;">

    <!-- MODAL MESSAGE CONTAINER -->
    <div class="modal fade" tabindex="-1" id="message_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="message_modal_title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="message_modal_content">Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN APP CONTAINER - Dynamic -->
    <div class="container mt-4" id="init">
    </div>

    <!-- SYSTEM PARAMS CONTAINER updated on each call -->
    <div id="system_params" style="display: none;">
    </div>
</main>

    <!-- SCRIPTS : INTERNAL -->
    <script src="https://<?php echo $config['url']['server']; ?>/js/popper.min.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/bootstrap.min.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/nacl.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/nacl-util.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/source.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/paginate.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/grab.js"></script>
    <script src="https://<?php echo $config['url']['server']; ?>/js/src/send.js"></script>
    <script id="canvas_js" src="https://<?php echo $config['url']['server']; ?>/js/src/canvas.js"></script>
    <script id="hierachy_js" src="https://<?php echo $config['url']['server']; ?>/js/src/hierachy.js"></script>


    <!-- MAIN JAVASCRIPT -->
    <script type="text/javascript">
        // var url_server = "<?php echo $config['url']['server']; ?>";
        // var url_client = "<?php echo $config['url']['client']; ?>";
    </script>