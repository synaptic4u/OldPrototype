<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyApplications" novalidate>
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
                <h6 class="h6">Subscribe to an application</h6>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <?php if($selectApps === null){ ?>
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Available Applications
                        </span>
                    </legend>

                    <h6 class="h6">You are subscribed to all available applications.</h6>
                </fieldset>
                <?php }else{ ?>
                <?php echo $selectApps; ?>
                <?php }?>
            </div>
        </div>

        <?php if($selectApps === null){}else{ ?>
        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $applications_for_body_id; ?>','<?php echo $store; ?>','<?php echo $applications; ?>', this.form.id);">Subscribe</button>
            </div>
        </div>
        <?php }?>
    </div>
</form>

<?php if((int)$count > 0){ ?>

<div class="row mt-3 mb-2">
    <div class="col-sm-12 text-center">
        <h6 class="h6">Applications currently subscribed to</h6>
    </div>
</div>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="align-middle text-nowrap" scope="col">Application</th>
                <th class="align-middle text-nowrap" scope="col">Added On</th>
                <th class="align-middle text-nowrap" scope="col">Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($subscribedApps as $key => $value){ ?>
            <tr>
                <td class="align-middle text-nowrap"><?php echo $value['name']['value'] ;?></td>
                <td class="align-middle text-nowrap"><?php echo substr($value['addedon']['value'], 0, 10) ;?></td>
                <td class="align-middle text-nowrap"><button class="btn btn-sm btn-outline-danger m-0" type="button"
                        onclick="send('<?php echo $applications_for_body_id; ?>','<?php echo $remove; ?>','<?php echo $applications; ?>', ['<?php echo $hierachyid['value']; ?>','<?php echo $detid['value']; ?>','<?php echo $value['appid']['value']; ?>']);">Remove</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php }else{ ?>
<div class="row mx-0 mt-2">
    <div class="col-sm-12 text-center">
        <h5 class="h5">Subscribed applications display here</h5>
        <p class="text-start mx-2 px-2">You are currently not subscribed to any applications for this
            organization.</p>
    </div>
</div>
<?php } ?>