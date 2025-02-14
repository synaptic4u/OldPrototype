<li class="row" id="list<?php echo str_replace(' ', '', $hierachy['name']); ?>">
    <div class="col-auto nav-link hierachy-link justify-content mx-0 px-0">
        <button type="button" id="test-button" class="btn btn-outline-secondary btn-sm mx-2"
            onclick="buttonManageState(this);send('hierachy-form', '<?php echo $calls['create']; ?>', '<?php echo $calls['subhierachy']; ?>', ['<?php echo $hierachy['hierachyid']; ?>','<?php echo $hierachy['hierachysubid']; ?>']);">
            <i class="m-0 bi bi-list-ul"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm me-2"
            onclick="buttonManageState(this);send('hierachy-form', '<?php echo $calls['create']; ?>', '<?php echo $calls['subhierachy']; ?>', ['<?php echo $hierachy['hierachyid']; ?>','<?php echo $hierachy['hierachysubid']; ?>','<?php echo $calls['nested']; ?>']);">
            <i class="m-0 bi bi-list-nested"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm me-2"
            onclick="openAccordion('buttonOrgDet');buttonManageState(this);send('collapseOrgDetBody', '<?php echo $calls['detail']; ?>', '<?php echo $calls['hierachy']; ?>', ['<?php echo $hierachy['hierachyid']; ?>','<?php echo $hierachy['hierachysubid']; ?>']);">
            <i class="m-0 bi bi-gear"></i>
        </button>
    </div>
    <a class="nav-link px-3 hierachy-link ariatoggler col text-start" data-bs-toggle="collapse"
        href="#collapse<?php echo str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]/', '', $hierachy['name'])); ?>"
        role="button" aria-expanded="false"
        aria-controls="collapse<?php echo str_replace(' ', '', $hierachy['name']); ?>"
        onclick="manageState(this);showActive(this);">
        <span class="" id="user-profile-name">
            <?php echo $hierachy['name']; ?>
        </span>
        <span class="right-icon ms-auto">
            <i class="bi bi-chevron-right"></i>
        </span>
    </a>
    <div class="collapse ms-1"
        id="collapse<?php echo str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]/', '', $hierachy['name'])); ?>">
        <div>
            <ul class="navbar-nav ps-1" id="ul<?php echo str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]/', '', $hierachy['name'])); ?>">
                <?php foreach ($hierachy['ids'] as $nest) {
    echo $nest;
}?>
            </ul>
        </div>
    </div>
</li>