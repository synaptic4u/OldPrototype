<div id="<?php echo $approles_for_form_id; ?>">
</div>

<div class="row mt-3 mb-2">
    <div class="col-sm-12 text-center">
        <h5 class="h5"><?php echo $hierachyname; ?></h5>
        <h6 class="h6">Organization Application List</h6>
        <h6 class="h6">Edit to define permission roles.</h6>
    </div>
</div>
<div class="m-2 table-responsive-sm">
<?php if((int)$count > 0){ ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Adden On</th>
                <?php if((int)$canedit === 1){ ?><th scope="col">Edit</th><?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apps as $key => $value) { ?>
            <tr>
                <td class="align-middle text-nowrap"><?php echo $value['name']['value']; ?></td>
                <td class="align-middle text-nowrap"><?php echo substr($value['updatedon']['value'], 0, 10); ?></td>
                <?php if((int)$canedit === 1){ ?>
                <td class="align-middle text-nowrap">
                    <button class="btn btn-sm btn-outline-secondary m-0" type="button" onclick="send('<?php echo $approles_for_form_id; ?>','<?php echo $edit; ?>','<?php echo $approles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'appid' : '<?php echo $value['appid']['value']; ?>'});">Edit</button>                   
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<?php }else{ ?>
    <h6 class="h6">Your organization has not yet subscribed to any applications.</h6>
    <hr class="w-100 bold">
<?php }?>
</div>
