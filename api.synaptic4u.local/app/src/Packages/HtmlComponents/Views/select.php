<fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
    <legend class="w-auto float-none">
        <span class="ps-2 pe-2 h6">
            <?php echo $headings['legend']; ?>
        </span>
    </legend>

    <select class="form-select form-control <?php echo $headings['required']; ?>"
        name="<?php echo $headings['name']; ?>" aria-dssescribedby="<?php echo $headings['name']; ?>"
        id="<?php echo $headings['id']; ?>" <?php echo $headings['required']; ?>>
        <option value="">Please select a type</option>
        <?php foreach ($select as $key => $value) { ?>
        <option value="<?php echo $value['appid']; ?>"
            <?php echo ((int)$value['selected'] === 1) ? ' selected' : ''; ?>>
            <?php echo $value['name']['value']; ?>
        </option>
        <?php } ?>
    </select>
    <div class="valid-feedback">
        Looks good.
    </div>
    <div class="invalid-feedback">
        <?php echo $headings['invalid_msg']; ?>
    </div>
</fieldset>