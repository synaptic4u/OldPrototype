<div class="row">
    <div class="col text-center">
        <h6 class="h6">Add a role</h6>
    </div>
</div>
<div class="row">
    <div class="col">
        <form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="<?php echo $app_roles_row_roles_form; ?>" novalidate>
            <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
            <input minlength="1" type="hidden" name="appid" value="<?php echo $appid['value']; ?>" required>
            <input minlength="1" type="hidden" name="moduleid" value="<?php echo $moduleid['value']; ?>" required>
            
            <div class="container m-0 p-0">
                <div class="row mt-2" id="default-types">
                    <div class="col-sm-12">
                        <?php echo $roles['html'];?>
                    </div>
                </div>

                <div class="row mt-2 mb-2">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-sm btn-outline-primary" type="button"
                            onclick="grab('<?php echo $app_roles_row_roles; ?>','<?php echo $add; ?>','<?php echo $approles; ?>', this.form.id);">Add</button>
                    </div>
                </div>
            </div>
        </form>
        <hr class="w-100 bold">
    </div>
</div>
<div class="row">
    <div class="col text-center">
        <h6 class="h6">Module's Roles</h6>
    </div>
</div>
<?php if((int)$count > 0){ ?>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="align-middle text-nowrap" scope="col">Role</th>
                <th class="align-end text-nowrap" style="width:20%" scope="col">Remove</th>
                <!-- <th class="align-middle text-nowrap" scope="col">Updated On</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mod_roles as $key => $value) { ?>
            <tr>
                <td class="align-middle text-nowrap"><?php echo $value['role']['value']; ?></td>
                <td class="align-end text-nowrap">
                    <div class="row mt-2 mb-2">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-sm btn-outline-danger" type="button"
                                onclick="send('<?php echo $app_roles_row_roles; ?>','<?php echo $remove; ?>','<?php echo $approles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'appid' : '<?php echo $appid['value']; ?>', 'moduleid' : '<?php echo $moduleid['value']; ?>', 'roleid' : '<?php echo $value['roleid']['value']; ?>'});">Remove</button>
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
        <h6 class="h6">No roles have been assigned.</h6>
    </div>
</div>
<?php } ?>