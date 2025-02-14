<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyDet" novalidate>
    <div class="container m-0 p-0">

        <div class="row">
            <?php if ((int)$canedit === 1) { ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$canedit === 1) ? 'col-md-8 ' : ''; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname; ?></h5>
            </div>

            <?php if ((int)$canedit === 1) { ?>
            <div class="col-md-2 col-sm-12">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-secondary float-end text-nowrap" type="button"
                        onclick="send('<?php echo $hierachy_det_form_id; ?>','<?php echo $edit; ?>','<?php echo $hierachy; ?>', ['<?php echo $hierachyid; ?>', '<?php echo $detid; ?>']);">Edit
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Parent Organization
                        </span>
                    </legend>
                    <p><?php echo $parentorg; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Name
                        </span>
                    </legend>
                    <p><?php echo $hierachyname; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Type
                        </span>
                    </legend>
                    <p><?php echo $type; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Description
                        </span>
                    </legend>
                    <p><?php echo $hierachydescription; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Visibility
                        </span>
                    </legend>
                    <p><?php echo $visible; ?></p>
                </fieldset>
            </div>
        </div>
    </div>
</form>