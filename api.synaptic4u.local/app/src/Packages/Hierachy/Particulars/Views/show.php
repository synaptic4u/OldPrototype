<form method="POST" class="fading needs-validation text-break text-wrap m-1 p-1" id="HierachyParticulars" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
    <input minlength="1" type="hidden" name="detid" value="<?php echo $detid['value']; ?>" required>
    <input minlength="1" type="hidden" name="particularid" value="<?php echo $particularid['value']; ?>" required>
    <div class="container m-0 p-0">

        <div class="row">
            <?php if ((int)$canedit === 1) { ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$canedit === 1) ? 'col-md-8 ' : ''; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname; ?></h5>
            </div>

            <?php if ((int)$canedit === 1) { ?>
            <div class="col-md-2 col-sm-12">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-secondary float-end text-nowrap" type="button"
                        onclick="send('<?php echo $particulars_for_body_id; ?>','<?php echo $edit; ?>','<?php echo $particulars; ?>', ['<?php echo $hierachyid['value']; ?>', '<?php echo $detid['value']; ?>', '<?php echo $particularid['value']; ?>']);">Edit
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0 text-center">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Logo
                        </span>
                    </legend>
                    <?php if (null === $logo['value']) { ?>
                    <img src="https://<?php echo $config['url']['server']; ?>/hierachy/default.webp" alt="image of Synaptic4U ?>"
                        width="55" height="45" class="rounded me-2" id="hierachy-logo">
                    <?php } else { ?>
                    <img src="<?php echo $logo['value']; ?>" alt="image of <?php echo $hierachyname; ?>" width="55"
                        height="45" class="rounded me-2" id="hierachy-logo">
                    <?php } ?>
                </fieldset>
            </div>
        </div>
 
        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Website
                        </span>
                    </legend>
                    <p><?php echo $website['value'] ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Contact
                        </span>
                    </legend>
                    <p><?php echo $contactuser['value']; ?>"</p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Phone Number
                        </span>
                    </legend>
                    <p><?php echo $phone['value']; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Physical Address
                        </span>
                    </legend>
                    <p class="text-break text-wrap"><?php echo $address['value']; ?></p>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Is Postal
                        </span>
                    </legend>
                    <p class="text-break text-wrap">
                        <?php echo ((int)$ispostal['value'] === 0) ? 'Postal and Physical address are different.' : 'Postal and Physical address are the same.'; ?>
                    </p>
                </fieldset>
            </div>
        </div>

        <?php if ((int)$ispostal['value'] === 0) { ?>
        <div class="row mt-2" id="postal-address">
            <div class="col-sm-12">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Organization Postal Address
                        </span>
                    </legend>
                    <p class="text-break text-wrap"><?php echo $postal['value']; ?></p>
                </fieldset>
            </div>
        </div>
        <?php } ?>

    </div>
</form>