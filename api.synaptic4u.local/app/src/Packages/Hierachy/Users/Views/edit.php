<form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyUsers" novalidate>
    <input minlength="1" type="hidden" name="hierachyid" value="<?php echo $hierachyid['value']; ?>" required>
    <input minlength="1" type="hidden" name="detid" value="<?php echo $detid['value']; ?>" required>
    <input minlength="1" type="hidden" name="userid" value="<?php echo $userid['value']; ?>" required>
    <div class="container m-0 p-0">
        <div class="row">
        <?php if((int)$canedit === 1){ ?>
            <div class="col-md-2 col-sm-0">
            </div>
            <?php } ?>

            <div class="<?php echo ((int)$canedit === 1) ? 'col-md-8 ' : '' ; ?>col-sm-12 text-center">
                <h5 class="h5"><?php echo $hierachyname; ?></h5>
            </div>

            <?php if((int)$canedit === 1){ ?>
            <div class="col-md-2 col-sm-12 ps-0">
                <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom">
                    <button class="btn btn-sm btn-outline-danger m-0" type="button" id=""
                        onclick="send('<?php echo $users_for_body_id; ?>','<?php echo $delete; ?>','<?php echo $users; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'userid' : '<?php echo $userid['value']; ?>'});">
                        Delete
                    </button>
                </div>
            </div>
        <?php } ?>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h6 class="h6">Edit your organization's user / personnel</h6>
            </div>
        </div>
        
        <div class="row mt-2" id="">
            <div class="col-sm-6">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Maintained By
                        </span>
                    </legend>
                    <div class="">
                        <?php echo (!is_null($maintainedby['value'])) ? $maintainedby['value'] : ''; ?>
                    </div>
                </fieldset>
            </div>

            <div class="col-sm-6">
                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                    <legend class="w-auto float-none">
                        <span class="ps-2 pe-2 h6">
                            Last Edited On
                        </span>
                    </legend>
                    <div class="">
                        <?php echo (!is_null($updatedon['value'])) ? $updatedon['value'] : ''; ?>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                Firstname
                            </span>
                        </legend>
                        <input minLength="2" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($firstname['pass'])) ? $firstname['message'] : ''; ?>"
                            type="text" placeholder="Firstname" aria-describedby="user"
                            name="firstname" value="<?php echo (!is_null($firstname['value'])) ? $firstname['value'] : ''; ?>" 
                            <?php echo ((int)$personnel['value'] === 0 || (int)$invite === 2)? 'disabled': '';?> 
                            required >
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                            Firstname must be a minimum of 2 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                            Surname
                            </span>
                        </legend>
                        <input minLength="2" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($surname['pass'])) ? $surname['message'] : ''; ?>"
                            type="text" placeholder="Surname" aria-describedby="surname"
                            name="surname" value="<?php echo (!is_null($surname['value'])) ? $surname['value'] : ''; ?>" 
                            <?php echo ((int)$personnel['value'] === 0 || (int)$invite === 2)? 'disabled': '';?> 
                            required >
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                        Surname must be a minimum of 2 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                            Contact Number
                            </span>
                        </legend>
                        <input minLength="10" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($contactnu['pass'])) ? $contactnu['message'] : ''; ?>"
                            type="text" placeholder="Contact Number" aria-describedby="contactnu"
                            name="contactnu" value="<?php echo (!is_null($contactnu['value'])) ? $contactnu['value'] : ''; ?>" 
                            <?php echo ((int)$personnel['value'] === 0 || (int)$invite === 2)? 'disabled': '';?> 
                            required >
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                        Surname must be a minimum of 2 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                Email
                            </span>
                        </legend>
                        <input minLength="7" 
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($email['pass'])) ? $email['message'] : ''; ?>"
                            type="text" placeholder="Email" aria-describedby="email"
                            name="email" value="<?php echo (!is_null($email['value'])) ? $email['value'] : ''; ?>" 
                            <?php echo ((int)$personnel['value'] === 0 || (int)$invite === 2)? 'disabled': '';?> 
                            required>
                        <div class="valid-feedback">
                            Looks good.
                        </div>
                        <div class="invalid-feedback">
                            Email must be a minimum of 7 characters.
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2" id="user_personnel">
                <div class="col-sm-12">
                    <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                        <legend class="w-auto float-none">
                            <span class="ps-2 pe-2 h6">
                                User / Personnel
                            </span>
                        </legend>
                        <div class="form-check form-switch">
                            <p>A user is personnel who will be able to use the system.<br>
                                An email invite will be sent to them asking them to register on the system if left as "User".<br>
                                Until then they will be classified as personnel on the system.<br>
                                Personnel on the system are unable to use the system but will show in all applications used by your organization.</p>
                            <input class="form-check-input"
                                    type="checkbox" name="personnel" id="personnel" href="#personnel"
                                    role="button" onclick="userToggle(this.id, 'user-personnel');"
                                    aria-controls="personnel" <?php echo ((int) $personnel['value'] === 1) ? ' checked' : ''; ?>>
                            <label class="form-check-label" for="personnel" data-bs-toggle="tooltip"
                                data-bs-html="true" id="user-personnel"
                                title="Toggle to select User or Personnel.">
                                <?php echo ((int) $personnel['value'] === 0) ? '? User' : '? Personnel'; ?>
                            </label>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <?php echo $roles; ?>
                </div>
            </div>

        <div class="row mt-2 mb-2">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('<?php echo $users_for_form_id; ?>','<?php echo $update; ?>','<?php echo $users; ?>', this.form.id);">Update</button>
            </div>
        </div>
    </div>
</form>
