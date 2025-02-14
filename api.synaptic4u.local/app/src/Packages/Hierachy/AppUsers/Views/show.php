<div class="row">
    <div class="col text-center">
        <h6 class="h6">Include a user</h6>
    </div>
</div>
<div class="row">
    <div class="col">
        <form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="<?php echo $app_roles_row_user_form; ?>" novalidate>
            <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
            <input minlength="1" type="hidden" name="appid" value="<?php echo $appid['value']; ?>" required>
            <input minlength="1" type="hidden" name="moduleid" value="<?php echo $moduleid['value']; ?>" required>
            
            <div class="container m-0 p-0">
                <div class="row mt-2"
                    id="default-types">
                    <div class="col-sm-12">
                        <?php echo $user_select['html'];?>
                    </div>
                </div>

                <div class="row mt-2" id="app_roles_row_user_toggle_div_<?php echo $moduleid['id']; ?>">
                    <div class="col-sm-12">
                        <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                            <legend class="w-auto float-none">
                                <span class="ps-2 pe-2 h6">
                                    Include / Exclude
                                </span>
                            </legend>
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                        type="checkbox" name="include_exclude" id="user_roles_include_exclude_<?php echo $moduleid; ?>" href="#user_roles_include_exclude_<?php echo $moduleid; ?>"
                                        role="button" onclick="userIncludeExclude(this.id, 'user_roles_include_exclude_toggle_<?php echo $moduleid['id']; ?>');"
                                        aria-controls="user_roles_include_exclude_<?php echo $moduleid; ?>" >
                                    <label class="form-check-label" for="user_roles_include_exclude_<?php echo $moduleid; ?>" data-bs-toggle="tooltip"
                                        data-bs-html="true" id="user_roles_include_exclude_toggle_<?php echo $moduleid['id']; ?>"
                                        title="Toggle to include or exclude the user.">
                                        Include
                                    </label>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row mt-2 mb-2">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-sm btn-outline-primary" type="button"
                            onclick="grab('<?php echo $app_roles_row_user; ?>','<?php echo $store; ?>','<?php echo $appusers; ?>', this.form.id);">Add</button>
                    </div>
                </div>
            </div>
        </form>
        <hr class="w-100 bold">
    </div>
</div>
<div class="row">
    <div class="col text-center">
        <h6 class="h6">Module's included users</h6>
    </div>
</div>

<?php if((int)$count > 0){ ?>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="align-middle text-nowrap" scope="col">User</th>
                <th class="align-middle text-nowrap" scope="col">Include / Exclude</th>
                <th class="align-end text-nowrap" style="width:20%" scope="col">Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $key => $value) { ?>
            <tr>
                <td class="align-middle text-nowrap"><?php echo $value['user']['value']; ?></td>
                <td class="align-middle text-nowrap"><?php echo ((int)$value['include_exclude']['value'] === 0) ? 'Included' : 'Excluded'; ?></td>
                <td class="align-end text-nowrap">
                    <div class="row mt-2 mb-2">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-sm btn-outline-danger" type="button"
                            onclick="send('<?php echo $app_roles_row_user; ?>','<?php echo $remove; ?>','<?php echo $appusers; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'appid' : '<?php echo $appid['value']; ?>', 'moduleid' : '<?php echo $moduleid['value']; ?>', 'userid' : '<?php echo $value['userid']['value']; ?>'});">Remove</button>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php }else{ ?>
<div class="row">
    <div class="col text-center">
        <h6 class="h6">No users have been included.</h6>
    </div>
</div>
<?php } ?>