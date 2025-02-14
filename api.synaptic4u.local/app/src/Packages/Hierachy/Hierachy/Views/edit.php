<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyDet" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
    <input minlength="1" type="hidden" name="hierachysubid" value="<?php echo $hierachysubid['value']; ?>" required>
    <input minlength="1" type="hidden" name="hierachydetid" value="<?php echo $hierachydetid['value']; ?>" required>
    <div class="container m-0 p-0">
        <div class="toast mx-auto mb-4 border-danger hide" role="alert" aria-live="assertive" aria-atomic="true"
            id="deleteHierachyDetConfirm">
            <div class="toast-body text-center">
                <strong>If you <span class="text-danger">DELETE</span> <span
                        class="text-decoration-underline"><?php echo $hierachyname['value']; ?></span>?<br>
                    This and all dependant structures, apps, perssonel will be
                    <span class="text-danger">DELETED!</span></strong>
                <div class="mt-2 pt-2 border-top text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="send('<?php echo $hierachy_det_form_id; ?>','<?php echo $delete; ?>','<?php echo $hierachy; ?>', ['<?php echo $hierachyid['value']; ?>', '<?php echo $hierachydetid['value']; ?>']);">Delete</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
                        onclick="hideToast('deleteHierachyDetConfirm');">Close</button>
                </div>
            </div>
        </div>

        <div class="row">
            <?php if((int)$candelete === 1){ ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$candelete === 1) ? 'col-md-8 ' : '' ; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname['value']; ?></h5>
            </div>

            <?php if((int)$candelete === 1){ ?>
            <div class="col-md-2 col-sm-12 ps-0">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-danger" type="button" id="deleteHierachyDet"
                        onclick="showToast('deleteHierachyDetConfirm');">Delete
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
                    <h6 class="h6 ms-2 ps-2">
                        <?php echo $parentorg; ?></h6>
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
                    <input minLength="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($hierachyname['pass'])) ? $hierachyname['message'] : ''; ?>"
                        type="text" placeholder="Organization Name" aria-describedby="hierachyname" name="hierachyname"
                        value="<?php echo (!is_null($hierachyname['pass'])) ? $hierachyname['value'] : ''; ?>"
                        required />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization Name is required and a
                        minimum of 2
                        characters.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12" id="hierachy-type">
                <?php echo $select_hierachy_type; ?>
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
                    <textarea minLength="20" rows="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($hierachydescription['pass'])) ? $hierachydescription['message'] : ''; ?>"
                        type="text" placeholder="Organization Description" aria-describedby="hierachydescription"
                        name="hierachydescription"><?php echo (!is_null($hierachydescription['pass'])) ? $hierachydescription['value'] : ''; ?></textarea>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization Description is required
                        and a minimum
                        of 20
                        characters.
                    </div>
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
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input  <?php echo (!is_null($hierachyvisibility['pass'])) ? $hierachyvisibility['message'] : ''; ?>"
                            type="checkbox" id="hierachyvisibility" name="hierachyvisibility"
                            <?php echo (!is_null($hierachyvisibility['pass'])) ? (((int) $hierachyvisibility['value'] === 1) ? ' checked' : '') : ''; ?>>
                        <label class="form-check-label" for="hierachyvisibility" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="If set to false, all children organizations staff will be unable to see above this organization. Exceptions are assigned with role's permissions.">Toggle
                            visibility</label>
                    </div>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization Name is required and a
                        minimum of 2
                        characters.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $hierachy_det_form_id; ?>','<?php echo $update; ?>','<?php echo $hierachy; ?>', this.form.id);">Update</button>
            </div>
        </div>
    </div>
</form>