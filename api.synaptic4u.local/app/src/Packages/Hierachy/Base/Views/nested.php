<li class="row" id="list<?php echo str_replace(' ', '', $hierachy['name']); ?>">
    <div class="col-auto nav-link hierachy-link justify-content mx-0 px-0">
        <button type="button" class="btn btn-outline-secondary btn-sm mx-2"
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
    <a id="<?php echo str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]/', '', $hierachy['name'])); ?>"
        class="nav-link px-3 col text-start" onclick="showActive(this);">
        <span class="">
            <?php echo $hierachy['name']; ?>
        </span>
    </a>
</li>