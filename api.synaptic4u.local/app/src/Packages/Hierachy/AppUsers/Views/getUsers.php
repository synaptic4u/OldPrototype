<fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
    <legend class="w-auto float-none">
        <span class="ps-2 pe-2 h6">
            Organization Users
        </span>
    </legend>
    <select class="form-select form-control" name="userid" required>
        <option value="">Please select a user</option>
        <?php foreach ($users as $key => $value) { ?>
        <option value="<?php echo $value['userid']['value']; ?>">
            <?php echo $value['user']['value']; ?>
        </option>
        <?php } ?>
    </select>

    <div class="valid-feedback">
        Looks good.
    </div>
    <div class="invalid-feedback">
        A user is required. 
    </div>
</fieldset>
