<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyTypes" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
    <input minlength="1" type="hidden" name="detid" value="<?php echo $detid['value']; ?>" required>
    <input minlength="1" type="hidden" name="hierachytypeid" value="<?php echo $hierachytypeid['value']; ?>" required>
    <div class="container m-0 p-0">
        <div class="row">
        <?php if((int)$canedit === 1){ ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$canedit === 1) ? 'col-md-8 ' : '' ; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname; ?></h5>
            </div>

            <?php if((int)$canedit === 1){ ?>
            <div class="col-md-2 col-sm-12 ps-0">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-danger m-0" type="button" id=""
                        onclick="send('<?php echo $types_for_body_id; ?>','<?php echo $delete; ?>','<?php echo $types; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'hierachytypeid' : '<?php echo $hierachytypeid['value']; ?>'});">
                        Delete
                    </button>
                </div>
            </div>
        <?php } ?>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h6 class="h6">Edit your organization type</h6>
            </div>
        </div>
        
        <div class="row mt-2" id="">

            <div class="col-sm-6">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Maintained By
                        </span>
                    </legend>
                    <div class="">
                        <?php echo (!is_null($user['value'])) ? $user['value'] : ''; ?>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-6">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Last Edited On
                        </span>
                    </legend>
                    <div class="">
                        <?php echo (!is_null($updatedon['value'])) ? $updatedon['value'] : ''; ?>
                    </div>
                </fieldset>
            </div>
        
        </div>

        <div class="row mt-2"
            id="default-types">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Type
                        </span>
                    </legend>
                    <input minLength="3" 
                        class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($type['pass'])) ? $type['message'] : ''; ?>"
                        type="text" placeholder="Type" aria-describedby="type"
                        name="type" value="<?php echo (!is_null($type['value'])) ? $type['value'] : ''; ?>" >
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        An organization type must be a minimum of 3 characters.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2"
            id="default-types">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Disable / Enable
                        </span>
                    </legend>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                                type="checkbox" name="exclude" id="exclude" href="#exclude"
                                role="button" 
                                aria-controls="exclude" <?php echo ((int) $exclude['value'] === 0) ? ' checked' : ''; ?>>
                            <label class="form-check-label" for="exclude" data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="Enables or disables the type available for selection.">
                                <?php echo ((int) $exclude['value'] === 0) ? 'Enabled' : 'Disabled'; ?>
                            </label>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $types_for_body_id; ?>','<?php echo $update; ?>','<?php echo $types; ?>', this.form.id);">Update</button>
            </div>
        </div>
    </div>
</form>
