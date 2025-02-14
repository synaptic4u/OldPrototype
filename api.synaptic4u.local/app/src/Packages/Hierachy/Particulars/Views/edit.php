<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyParticulars" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
    <input minlength="1" type="hidden" name="detid" value="<?php echo $detid['value']; ?>" required>
    <input minlength="1" type="hidden" name="particularid" value="<?php echo $particularid['value']; ?>" required>
    <input minlength="1" type="hidden" name="imageid" value="<?php echo $imageid['value']; ?>" required>
    <div class="container m-0 p-0">
        <div class="toast mx-auto mb-4 border-danger hide" role="alert" aria-live="assertive" aria-atomic="true"
            id="deleteHierachyParticularConfirm">
            <div class="toast-body text-center">
                <strong>Are you sure to <span class="text-danger">DELETE</span> <span
                        class="text-decoration-underline"><?php echo $hierachyname; ?></span> particulars?<br>
                    This and the logo will be
                    <span class="text-danger">DELETED!</span></strong>
                <div class="mt-2 pt-2 border-top text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="send('<?php echo $particulars_for_body_id; ?>','<?php echo $delete; ?>','<?php echo $particulars; ?>', ['<?php echo $hierachyid['value']; ?>', '<?php echo $detid['value']; ?>', '<?php echo $particularid['value']; ?>']);">Delete</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
                        onclick="hideToast('deleteHierachyParticularConfirm');">Close</button>
                </div>
            </div>
        </div>

        <div class="row">
            <?php if((int)$candelete === 1) { ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$candelete === 1) ? 'col-md-8 ' : '' ; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname; ?></h5>
            </div>

            <?php if((int)$candelete === 1) { ?>
            <div class="col-md-2 col-sm-12 ps-0">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-danger" type="button" id="deleteHierachyParticular"
                        onclick="showToast('deleteHierachyParticularConfirm');">Delete
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
                            Organization Logo
                        </span>
                    </legend>
                    <div class="col-sm-12 text-center">
                        <?php if (null === $logo['value']) {?>
                        <img loading="lazy" class="img-thumbnail rounded " width="62.22px" height="35px"
                            src="https://<?php echo $config['url']['server']; ?>/hierachy/default.webp" alt="image of Synaptic4U ?>"
                            id="hierachy-logo">
                        <?php } else { ?>
                        <img src="<?php echo $logo['value']; ?>" alt="image of <?php echo $hierachyname; ?>"
                            width="62.22px" height="35px"
                            class="img-thumbnail rounded  <?php echo (!is_null($logo['pass'])) ? $logo['message'] : ''; ?>"
                            id="hierachy-logo">
                        <?php } ?>
                    </div>
                    <div class="col-sm-12 mt-2">
                        <input class="form-control" type="file" name="hierachy_profile_logo" id="hierachy-profile-logo"
                            onchange="fileLoader.getFile(this.id, 'hierachy-logo', 'hierachy-profile-img');">
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                            Your Organization logo needs to be a valid jpeg or png image.
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Website
                        </span>
                    </legend>
                    <input minLength="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($website['pass'])) ? $website['message'] : ''; ?>"
                        type="text" placeholder="Organization's Website" aria-describedby="website" name="website"
                        value="<?php echo (!is_null($website['value'])) ? $website['value'] : ''; ?>" />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Your Organization website address cannot be empty.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <?php echo $selectUser; ?>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Phone Number
                        </span>
                    </legend>
                    <input minLength="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($phone['pass'])) ? $phone['message'] : ''; ?>"
                        type="text" placeholder="Phone Number" aria-describedby="phone" name="phone"
                        value="<?php echo (!is_null($phone['value'])) ? $phone['value'] : ''; ?>" />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        The Organization contact number is required.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Physical Address
                        </span>
                    </legend>
                    <textarea minLength="20" rows="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($address['pass'])) ? $address['message'] : ''; ?>"
                        type="text" placeholder="Physical Address" aria-describedby="address"
                        name="address"><?php echo (!is_null($address['value'])) ? $address['value'] : ''; ?></textarea>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        An organization physical address is required.
                    </div>
                </fieldset>
            </div>
        </div>


        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Is Postal
                        </span>
                    </legend>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input  <?php echo (!is_null($ispostal['pass'])) ? $ispostal['message'] : ''; ?>"
                            type="checkbox" name="hierachyispostal" id="hierachyispostal" href="#postal-address"
                            role="button" data-bs-toggle="collapse"
                            aria-expanded="<?php echo (!is_null($ispostal['value'])) ? (((int) $ispostal['value'] === 1) ? 'false' : 'true') : 'true'; ?>"
                            aria-controls="postal-address"
                            <?php echo (!is_null($ispostal['value'])) ? (((int) $ispostal['value'] === 1) ? ' checked' : '') : ''; ?>>
                        <label class="form-check-label" for="hierachyispostal" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="If set to true, the organization's postal address will be set to the physical address.">Toggle
                            visibility</label>
                    </div>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        Is postal toggler.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 <?php echo (!is_null($ispostal['value'])) ? (((int) $ispostal['value'] === 1) ? 'collapse' : 'show') : 'show'; ?>"
            id="postal-address">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Postal Address
                        </span>
                    </legend>
                    <textarea minLength="20" rows="2"
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($postal['pass'])) ? $postal['message'] : ''; ?>"
                        type="text" placeholder="Postal postal" aria-describedby="postal"
                        name="postal"><?php echo (!is_null($postal['value'])) ? $postal['value'] : ''; ?></textarea>
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        An organization postal address is required.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $particulars_for_body_id; ?>','<?php echo $update; ?>','<?php echo $particulars; ?>', this.form.id);">Update</button>
            </div>
        </div>
    </div>
</form>