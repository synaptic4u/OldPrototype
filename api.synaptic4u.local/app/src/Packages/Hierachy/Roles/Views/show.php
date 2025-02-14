
<?php if (1 === (int) $canedit) { ?>
<div id="<?php echo $roles_for_form_id; ?>">
    <form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyRoles" novalidate>
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
                    <h6 class="h6">Create your own organization roles</h6>
                </div>
            </div>

            <div class="row mt-2"
                id="default-roles">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                Organization Custom Role
                            </span>
                        </legend>
                        <input minLength="3" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($role['pass'])) ? $role['message'] : ''; ?>"
                            type="text" placeholder="Custom Role" aria-describedby="role"
                            name="role" value="<?php echo (!is_null($role['value'])) ? $role['value'] : ''; ?>" >
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                            An organization custom role must be a minimum of 3 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2"
                id="default-roles">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                Permission Options
                            </span>
                        </legend>
                        <p>Hover over the label for a explanation.</p>
                        
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox" name="create" id="checkbox_create" href="#checkbox_create"
                                role="button" 
                                aria-controls="checkbox_create" <?php echo (isset($role['create']['value']) && 1 === (int) $role['create']['value']) ? ' checked' : ''; ?> >
                            <label class="form-check-label" for="checkbox_create" data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="Enables or disables the role's privilege to be able to create items in your organizations applications.">
                                    Create
                            </label>
                        </div>
                        
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox" name="view" id="checkbox_view" href="#checkbox_view"
                                role="button" 
                                aria-controls="checkbox_view"  <?php echo (isset($role['view']['value']) && 1 === (int) $role['view']['value']) ? ' checked' : ''; ?>>
                            <label class="form-check-label" for="checkbox_view" data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="Enables or disables the role's privilege to be able to view items in your organizations applications.">
                                    View
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox" name="edit" id="checkbox_edit" href="#checkbox_edit"
                                role="button" 
                                aria-controls="checkbox_edit" <?php echo (isset($role['edit']['value']) && 1 === (int) $role['edit']['value']) ? ' checked' : ''; ?> >
                            <label class="form-check-label" for="checkbox_edit" data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="Enables or disables the role's privilege to be able to edit items in your organizations applications.">
                                    Edit
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox" name="delete" id="checkbox_delete" href="#checkbox_delete"
                                role="button" 
                                aria-controls="checkbox_delete" <?php echo (isset($role['delete']['value']) && 1 === (int) $role['delete']['value']) ? ' checked' : ''; ?> >
                            <label class="form-check-label" for="checkbox_delete" data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="Enables or disables the role's privilege to be able to delete items in your organizations applications.">
                                    Delete
                            </label>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-sm btn-outline-primary" type="button"
                        onclick="grab('<?php echo $roles_for_body_id; ?>','<?php echo $store; ?>','<?php echo $roles; ?>', this.form.id);">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<div class="row mt-3 mb-2">
    <div class="col-sm-12 text-center">
        <h6 class="h6">Organization Roles</h6>
    </div>
</div>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="align-middle text-nowrap" scope="col">Role</th>
                <th class="align-middle text-nowrap" scope="col">Dated On</th>
                <th class="align-middle text-nowrap" scope="col">Added By</th>
                <th class="align-middle text-nowrap" scope="col">Create</th>
                <th class="align-middle text-nowrap" scope="col">View</th>
                <th class="align-middle text-nowrap" scope="col">Edit</th>
                <th class="align-middle text-nowrap" scope="col">Delete</th>
                <?php if (1 === (int) $canedit) { ?><th class="align-middle text-nowrap" scope="col">Disable / Enable</th><th class="align-middle text-nowrap" scope="col">Edit</th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rolesArray as $key => $value) { ?>
            <tr id="rolestoggle<?php echo $key; ?>">
                <td class="align-middle text-nowrap"><?php echo $value['role']['value']; ?></td>
                <td class="align-middle text-nowrap"><?php echo substr($value['datedon'], 0, 10); ?></td>
                <td class="align-middle text-nowrap"><?php echo $value['user']; ?></td>
                <td class="align-middle text-nowrap">
                    <span class="right-icon ms-auto">
                        <i class="bi bi-<?php echo (1 === (int) $value['create']) ? 'check-lg' : 'x-lg'; ?>"></i>
                    </span>
                </td>
                <td class="align-middle text-nowrap">
                    <span class="right-icon ms-auto">
                        <i class="bi bi-<?php echo (1 === (int) $value['view']) ? 'check-lg' : 'x-lg'; ?>"></i>
                    </span>
                </td>
                <td class="align-middle text-nowrap">
                    <span class="right-icon ms-auto">
                        <i class="bi bi-<?php echo (1 === (int) $value['edit']) ? 'check-lg' : 'x-lg'; ?>"></i>
                    </span>
                </td>
                <td class="align-middle text-nowrap">
                    <span class="right-icon ms-auto">
                        <i class="bi bi-<?php echo (1 === (int) $value['delete']) ? 'check-lg' : 'x-lg'; ?>"></i>
                    </span>
                </td>
                <?php if (1 === (int) $canedit) { ?>
                <td class="align-middle text-nowrap">
                <?php if (8 === (int) $value['roleid']['id']) { ?>
                    <span class="text-secondary">Set by System</span>
                <?php } else { ?>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox" name="exclude" id="exclude" href="#exclude"
                            role="button" onclick="send('rolestoggle<?php echo $key; ?>','<?php echo $toggle; ?>','<?php echo $roles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'roleid' : '<?php echo $value['roleid']['value']; ?>', 'exclude' : '<?php echo $value['exclude']; ?>', 'toggleid' : 'rolestoggle<?php echo $key; ?>'});"
                            aria-controls="exclude" <?php echo (0 === (int) $value['exclude']) ? ' checked' : ''; ?>>
                        <label class="form-check-label" for="exclude" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="Enables or disables the type available for selection.
Can affect dependant organizations."><?php echo (0 === (int) $value['exclude']) ? 'Enabled' : 'Disabled'; ?></label>
                    </div>
                    <?php } ?>
                </td>
                <td class="align-middle text-nowrap">
                    <?php if (3 === (int) $value['userid']) { ?>
                        <span class="text-primary">System</span>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-outline-secondary m-0" type="button" onclick="send('<?php echo $roles_for_form_id; ?>','<?php echo $edit; ?>','<?php echo $roles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'roleid' : '<?php echo $value['roleid']['value']; ?>'});">Edit</button>
                    <?php } ?>
                    
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <hr class="w-100 bold">
</div>
