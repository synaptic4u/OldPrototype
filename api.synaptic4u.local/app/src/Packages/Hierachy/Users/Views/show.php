<?php if((int)$canedit === 1){ ?>
<div id="<?php echo $users_for_form_id; ?>">
    <form method="POST" class="fading needs-validation text-wrap m-1 p-1" id="HierachyUsers" novalidate>
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
                    <h6 class="h6">Add Users / Personnel to your organization</h6>
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
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($user['firstname']['pass'])) ? $user['firstname']['message'] : ''; ?>"
                            type="text" placeholder="Firstname" aria-describedby="user"
                            name="firstname" value="<?php echo (!is_null($user['firstname']['value'])) ? $user['firstname']['value'] : ''; ?>" required >
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
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($user['surname']['pass'])) ? $user['surname']['message'] : ''; ?>"
                            type="text" placeholder="Surname" aria-describedby="surname"
                            name="surname" value="<?php echo (!is_null($user['surname']['value'])) ? $user['surname']['value'] : ''; ?>" required >
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
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($user['contactnu']['pass'])) ? $user['contactnu']['message'] : ''; ?>"
                            type="text" placeholder="Contact Number" aria-describedby="contactnu"
                            name="contactnu" value="<?php echo (!is_null($user['contactnu']['value'])) ? $user['contactnu']['value'] : ''; ?>" required >
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
                            class="w-100 p-2 ps-3 form-control required <?php echo (!is_null($user['email']['pass'])) ? $user['email']['message'] : ''; ?>"
                            type="email" placeholder="Email" aria-describedby="email"
                            name="email" value="<?php echo (!is_null($user['email']['value'])) ? $user['email']['value'] : ''; ?>"  required>
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
                    <?php echo $roles['html']; ?>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-sm btn-outline-primary" type="button"
                        onclick="grab('<?php echo $users_for_body_id; ?>','<?php echo $store; ?>','<?php echo $users; ?>', this.form.id);">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<div class="row mt-3 mb-2">
    <div class="col-sm-12 text-center">
        <h6 class="h6">Organization Users / Personnel</h6>
    </div>
</div>
<div class="m-2 table-responsive-sm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Updated On</th>
                <th scope="col">Role</th>
                <?php if((int)$canedit === 1){ ?><th scope="col">Edit</th><?php } ?>
            </tr>
        </thead>
        <tbody id="users_pagination_list">
            <?php echo $page; ?>
        </tbody>
    </table>
    <hr class="w-100 bold">
    <?php echo $list; ?>
</div>
