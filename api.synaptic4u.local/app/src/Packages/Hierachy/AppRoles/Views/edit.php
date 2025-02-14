
<div class="container m-0 p-0">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h5 class="h5"><?php echo $hierachyname; ?></h5>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12 text-center">
            <h6 class="h6">Edit your organization's application roles</h6>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-sm-6 ps-4">
            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                <legend class="w-auto float-none">
                    <span class="ps-2 pe-2 h6">
                        Application
                    </span>
                </legend>
                <div>
                    <h6 class="h6 ms-2 my-1"><?php echo $app_name;?></h6>
                </div>
            </fieldset>
        </div>

        <div class="col-sm-6 pe-4">
            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                <legend class="w-auto float-none">
                    <span class="ps-2 pe-2 h6">
                        Last Edited On
                    </span>
                </legend>
                <div>
                    <h6 class="h6 ms-2 my-1"><?php echo $app_last_edited; ?></h6>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-12 text-start">
            <div class="ps-3">
                <h6 class="h6">
                    The table displays the necessary permissions for each module.<br>
                    Select the "Module" name to set the permissions for each module.<br>
                    Users can be granted special access to each module, or have their access revoked.<br>
                    Only users belonging to this hierachy will feature.<br>
                    Roles can be inherited by child organizations
                </h6>
                <h6 class="h6">
                    Privilige comparison.<br>
                    Compare the selected role to enable access to use the module.
                </h6>
                <ul>
                    <li>View = V</li>
                    <li>Create = C</li>
                    <li>Edit = E</li>
                    <li>Delete = D</li>
                </ul>
            </div>
            
        </div>
    </div>

    <div class="m-2 table-responsive-sm">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="align-middle text-nowrap" scope="col">Module</th>
                    <th class="align-middle text-nowrap" scope="col">Parent Module</th>
                    <th class="align-middle text-nowrap" scope="col">View</th>
                    <th class="align-middle text-nowrap" scope="col">Create</th>
                    <th class="align-middle text-nowrap" scope="col">Edit</th>
                    <th class="align-middle text-nowrap" scope="col">Delete</th>
                    <!-- <th class="align-middle text-nowrap" scope="col">Role</th> -->
                    <th class="align-middle text-nowrap" scope="col">Maintained by</th>
                    <th class="align-middle text-nowrap" scope="col">Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($app_roles as $key => $value) { ?>
                <tr>
                    <td class="align-middle text-nowrap app_roles_parent_cells" role="button"  onclick="toggleRowDisplay(this, 'app_roles_row_<?php echo $value['modid']; ?>');"><?php echo $value['module']['value']; ?></td>
                    <td class="align-middle text-nowrap"><?php echo $value['parent_module']['value']; ?></td>
                    <td class="align-middle text-nowrap">
                        <span class="right-icon ms-auto">
                            <i class="bi bi-<?php echo ((int)$value['view']['value'] === 1) ? 'check-lg' : 'x-lg' ; ?>"></i>
                        </span>
                    </td>
                    <td class="align-middle text-nowrap">
                        <span class="right-icon ms-auto">
                            <i class="bi bi-<?php echo ((int)$value['create']['value'] === 1) ? 'check-lg' : 'x-lg' ; ?>"></i>
                        </span>
                    </td>
                    <td class="align-middle text-nowrap">
                        <span class="right-icon ms-auto">
                            <i class="bi bi-<?php echo ((int)$value['edit']['value'] === 1) ? 'check-lg' : 'x-lg' ; ?>"></i>
                        </span>
                    </td>
                    <td class="align-middle text-nowrap">
                        <span class="right-icon ms-auto">
                            <i class="bi bi-<?php echo ((int)$value['delete']['value'] === 1) ? 'check-lg' : 'x-lg' ; ?>"></i>
                        </span>
                    </td>
                    <td class="align-middle text-nowrap"><?php echo (is_null($value['maintainedby'])) ? 'Never edited' : $value['maintainedby']; ?></td>
                    <td class="align-middle text-nowrap"><?php echo (is_null($value['updatedon'])) ? 'Never updated' : substr($value['updatedon'], 0, 10); ?></td>
                </tr>
                <tr class="app_roles_hidden_rows" id="app_roles_row_<?php echo $value['modid']; ?>" style="display:none;">
                    
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr class="w-100 bold">
    </div>
</div>
