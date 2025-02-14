<fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
    <legend class="w-auto float-none">
        <span class="ps-2 pe-2 h6">
            Organizational Type
        </span>
    </legend>

    <select class="form-select form-control required" name="<?php echo $name; ?>"
        aria-describedby="<?php echo $name; ?>" id="<?php echo $id; ?>" required>
        <option value="">Please select a type</option>
        <?php foreach ($data['select'] as $key => $value) { ?>
        <option value="<?php echo $value['hierachytypeid']; ?>"
            <?php echo ((int)$value['selected'] === 1) ? ' selected' : ''; ?>><?php echo $value['value']; ?></option>
        <?php } ?>
    </select>
    <div class="valid-feedback">
        Looks good.
    </div>
    <div class="invalid-feedback">
        An Organization Type is required.
    </div>
</fieldset>