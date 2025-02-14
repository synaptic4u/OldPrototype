<fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
    <legend class="w-auto float-none">
        <span class="ps-2 pe-2 h6">
            Organization Role
        </span>
    </legend>
    <select class="form-select form-control <?php echo $headings['required']; ?> <?php echo (!is_null($roleid['pass'])) ? $roleid['message'] : ''; ?>"
        name="roleid" required>
        <option value="">Please select a role</option>
        <?php foreach ($roles as $key => $value) { ?>
        <option value="<?php echo $value['roleid']['value']; ?>" <?php echo ((int)$select === 1) ? 'data="'.$value['roleid']['id'].'" ': '' ; ?>
            <?php echo (isset($value['roleid']['selected']) && (int)$value['roleid']['selected'] === 1) ? ' selected' : ''; ?>>
            <?php echo ((int)$select === 1) ? $value['role']['value'].' : '.$value['view'].$value['create'].$value['edit'].$value['delete'] : $value['role']['value']; ?>
        </option>
        <?php } ?>
    </select>

    <div class="valid-feedback">
        Looks good.
    </div>
    <div class="invalid-feedback">
        A role is required. 
    </div>
</fieldset>
