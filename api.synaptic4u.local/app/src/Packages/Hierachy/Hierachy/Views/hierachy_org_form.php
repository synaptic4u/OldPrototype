<form method="POST" class="fading needs-validation text-wrap mt-0 pt-0" id="Hierachy" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid; ?>" required>
    <input minlength="1" type="hidden" name="hierachysubid" value="<?php echo $hierachysubid; ?>" required>
    <input minlength="1" type="hidden" name="nested" value="<?php echo $nested; ?>" required>
    <div class="container">
        <div class="row">
            <div class="col-12 nav-link hierachy-link justify-content m-0">
                <button type="button" class="btn btn-outline-secondary btn-sm m-0 right-icon ms-auto"
                    onclick="wipe.form('hierachy-form');" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Remove the form">
                    <i class="m-0 bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Name
                        </span>
                    </legend>
                    <input minLength="2"
                        class="w-100 p-2 form-control required <?php echo (!is_null($hierachyname['pass'])) ? $hierachyname['message'] : ''; ?>"
                        type="text" placeholder="Organization Name" aria-describedby="hierachyname" name="hierachyname"
                        value="<?php echo $hierachyname['value']; ?>" required />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization Name is required and a minimum of 2
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
                        class="w-100 p-2 form-control required <?php echo (!is_null($hierachydescription['pass'])) ? $hierachydescription['message'] : ''; ?>"
                        type="text" placeholder="Organization Description" aria-describedby="hierachydescription"
                        name="hierachydescription"><?php echo $hierachydescription['value']; ?></textarea>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization Description is required and a minimum
                        of 20
                        characters.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">

                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $hierachy_list; ?>','<?php echo $store; ?>','<?php echo $hierachy; ?>', this.form.id);">Save</button>
            </div>
        </div>

        <div class="px-3 my-3">
            <hr class="dropdown-divider">
        </div>
    </div>
</form>