
<!-- ORGANIZATION APPLICATIONS -->
<li>
    <div class="text-muted small fw-bold text-uppercase px-3">
        Organization Applications
    </div>
</li>               
<?php // Start outer loop
if ((int) $orgnization_applications_count > 0) {
    // array_shift($orgnization_applications);
    foreach ($orgnization_applications as $key => $app) {
        $menu = array_values($app['menu']); // X
?>

<!-- START OF DYNAMIC BUILD : Add dynamic icons-->
<li>
    <a class="nav-link px-3 sidebar-link ariatoggler" data-bs-toggle="collapse"
        href="#collapseOrg<?php echo $app['name']; ?>" role="button" aria-expanded="false"
        aria-controls="collapseOrg<?php echo $app['name']; ?>"
        onclick="manageState(this);showActive(this);">
        <span class="me-2">
            <i class="bi bi-briefcase"></i>
        </span>
        <span><?php echo $app['name']; ?></span>
        <span class="right-icon ms-auto">
            <i class="bi bi-chevron-right"></i>
        </span>

    </a>
    <div class="collapse" id="collapseOrg<?php echo $app['name']; ?>">
        <div>
            <ul class="navbar-nav ps-3">
                <?php // Start inner loop
                    $length = sizeof($menu);

        for ($cnt = 0; $cnt < $length; ++$cnt) { ?>
            <?php // Start of outer conditions
    if (($menu[$cnt]['submoduleid'] == $menu[$cnt + 1]['submoduleid']) || ($menu[$cnt]['submoduleid'] == $menu[$cnt - 1]['submoduleid'])) {
        ?>
        <?php // Start of inner condition1
        if (($menu[$cnt]['moduleid'] == $menu[$cnt]['submoduleid'])) {
            ?>
                <li>
                    <a class="nav-link px-3 sidebar-link ariatoggler" data-bs-toggle="collapse"
                        href="#collapseOrg<?php echo $menu[$cnt]['title']; ?>" role="button"
                        aria-expanded="false"
                        aria-controls="collapseOrg<?php echo $menu[$cnt]['title']; ?>"
                        onclick="manageState(this);showActive(this);">
                        <span class="me-2">
                            <i class="bi bi-app"></i>
                        </span>
                        <span><?php echo $menu[$cnt]['title']; ?></span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </a>
                    <div class="collapse" id="collapseOrg<?php echo $menu[$cnt]['title']; ?>">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <?php //  End of inner condition1
        } ?>
                                <?php // Start of inner condition2
        if (($menu[$cnt]['moduleid'] != $menu[$cnt]['submoduleid'])) {
            ?>
                                <li>
                                    <a onclick="showActive(this); send('init', '<?php echo $menu[$cnt]['method']; ?>', '<?php echo $menu[$cnt]['controller']; ?>', ['<?php echo $userid; ?>']);"
                                        class="nav-link px-3">
                                        <span class="me-2">
                                            <i class="bi bi-arrow-return-right"></i>
                                        </span>
                                        <span><?php echo $menu[$cnt]['title']; ?></span>
                                    </a>
                                </li>
                                <?php //  End of inner condition2
        } ?>
                                <?php // Start of inner condition1
        if (($menu[$cnt]['submoduleid'] != $menu[$cnt + 1]['submoduleid'])) {
            ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php //  End of inner condition1
        }
    } else { // DEFAULT TOP LEVEL LI
?>
                <li>
                    <a onclick="showActive(this); send('main_container', '<?php echo $menu[$cnt]['method']; ?>', '<?php echo $menu[$cnt]['controller']; ?>', ['<?php echo $userid; ?>']);"
                        class="nav-link px-3">
                        <span class="me-2">
                            <i class="bi bi-app"></i>
                        </span>
                        <span><?php echo $menu[$cnt]['title']; ?></span>
                    </a>
                </li>
                <?php // End of inner
    } // End of conditions
?>
                <?php // End of inner
        } ?>
                <?php // End of outer
    }
} else { //  If no applications are subscribed to?>
                <li>
                    <span class="text-muted small text-lowercase ps-4">
                        Subscribe to an application
                    </span>
                </li>
                <?php // End of outer
}?>
            </ul>
        </div>
    </div>
</li>
<li class="px-3 mt-1 mb-2">
    <hr class="dropdown-divider">
</li>