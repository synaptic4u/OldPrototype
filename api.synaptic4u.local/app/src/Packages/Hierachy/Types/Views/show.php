
<?php if((int)$canedit === 1){ ?>
<div id="<?php echo $types_for_form_id; ?>">
    <form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyTypes" novalidate>
        <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
        <input minlength="1" type="hidden" name="detid" value="<?php echo $detid['value']; ?>" required>
        <div class="container m-0 p-0">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h5 class="h5"><?php echo $hierachyname; ?></h5>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <h6 class="h6">Create your own organization types</h6>
                </div>
            </div>

            <div class="row mt-2"
                id="default-types">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                Organization Custom Type
                            </span>
                        </legend>
                        <input minLength="3" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($type['pass'])) ? $type['message'] : ''; ?>"
                            type="text" placeholder="Custom Type" aria-describedby="type"
                            name="type" value="<?php echo (!is_null($type['value'])) ? $type['value'] : ''; ?>" >
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                            An organization custom type must be a minimum of 3 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-sm btn-outline-primary" type="button"
                        onclick="grab('<?php echo $types_for_body_id; ?>','<?php echo $store; ?>','<?php echo $types; ?>', this.form.id);">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<div class="row mt-3 mb-2">
    <div class="col-sm-12 text-center">
        <h6 class="h6">Organization Types</h6>
    </div>
</div>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="align-middle text-nowrap" scope="col">Type</th>
                <th class="align-middle text-nowrap" scope="col">Dated On</th>
                <th class="align-middle text-nowrap" scope="col">Added By</th>
                <?php if((int)$canedit === 1){ ?><th class="align-middle text-nowrap" scope="col">Disable / Enable</th><th class="align-middle text-nowrap" scope="col">Edit</th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($typesArray as $key => $value) { ?>
            <tr id="toggle<?php echo $key; ?>">
                <td class="align-middle text-nowrap"><?php echo $value['type']['value']; ?></td>
                <td class="align-middle text-nowrap"><?php echo substr($value['datedon'], 0, 10); ?></td>
                <td class="align-middle text-nowrap"><?php echo $value['user']; ?></td>
                <?php if((int)$canedit === 1){ ?>
                <td class="align-middle text-nowrap">
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox" name="exclude" id="exclude" href="#exclude"
                            role="button" onclick="send('toggle<?php echo $key; ?>','<?php echo $toggle; ?>','<?php echo $types; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'hierachytypeid' : '<?php echo $value['hierachytypeid']['value']; ?>', 'exclude' : '<?php echo $value['exclude']; ?>', 'toggleid' : 'toggle<?php echo $key; ?>'});"
                            aria-controls="exclude" <?php echo ((int) $value['exclude'] === 0) ? ' checked' : ''; ?>>
                        <label class="form-check-label" for="exclude" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="Enables or disables the type available for selection.
Can affect dependant organizations."><?php echo ((int) $value['exclude'] === 0) ? 'Enabled' : 'Disabled'; ?></label>
                    </div>
                </td>
                <td class="align-middle text-nowrap">
                    <?php if((int)$value['userid'] === 3){ ?>
                        <span class="text-primary">System</span>
                    <?php }else{ ?>
                        <button class="btn btn-sm btn-outline-secondary m-0" type="button" onclick="send('<?php echo $types_for_form_id; ?>','<?php echo $edit; ?>','<?php echo $types; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'hierachytypeid' : '<?php echo $value['hierachytypeid']['value']; ?>'});">Edit</button>
                    <?php } ?>
                    
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <hr class="w-100 bold">
</div>
