<form method="POST" class="fading needs-validation" id="User" novalidate>

    <input type="hidden" name="userid" value="<?php echo $userid; ?>">

    <div class="container-flex mt-3" id="User">

        <div class="row justify-content-center">

            <div class="col-md-8 col-sm-12">

                <div class="container">

                    <div class="row">

                        <div class="col-md-2 col-sm-0">
                        </div>

                        <div class="col-md-8 col-sm-12 text-center">

                            <h5 class="h5"><?php echo $firstname['value']; ?>&nbsp;<?php echo $surname['value']; ?></h5>
                        </div>

                        <div class="col-md-2 col-sm-12 pe-md-4">

                            <div
                                class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom mr-md-2">

                                <button class="btn btn-sm btn-outline-danger" type="button"
                                    onclick="send('main_container','<?php echo $delete; ?>','<?php echo $userd; ?>', ['<?php echo $userid; ?>']);">Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">First Name</span></legend>

                                <input minLength="2"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($firstname['pass'])) ? $firstname['message'] : ''; ?>"
                                    type="text" placeholder="First Name" aria-describedby="firstname" name="firstname"
                                    value="<?php echo (!is_null($firstname['pass'])) ? $firstname['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    Your first name is required and a minimum of 2 characters.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Surname</span></legend>

                                <input minLength="2"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($surname['pass'])) ? $surname['message'] : ''; ?>"
                                    type="text" placeholder="Surname" aria-describedby="surname" name="surname"
                                    value="<?php echo (!is_null($surname['pass'])) ? $surname['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    Your surname is required and a minimum of 2 characters.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Email Address</span>
                                </legend>

                                <input minLength="10"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($email['pass'])) ? $email['message'] : ''; ?>"
                                    type="email" placeholder="Email Address" aria-describedby="email" name="email"
                                    value="<?php echo (!is_null($email['pass'])) ? $email['value'] : ''; ?>" required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 10 characters and a valid domain name is required.
                                    <?php echo (!is_null($email_exists)) ? 'This email is already in use!' : ''; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Contact Number</span>
                                </legend>

                                <input minLength="10"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($contactnu['pass'])) ? $contactnu['message'] : ''; ?>"
                                    type="text" placeholder="Contact Number" aria-describedby="contactnu"
                                    name="contactnu"
                                    value="<?php echo (!is_null($contactnu['pass'])) ? $contactnu['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 10 character is required.
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Password</span></legend>

                                <input minLength="15"
                                    class="w-100 p-2 form-control required <?php echo (!is_null($passkey['pass'])) ? $passkey['message'] : ''; ?>"
                                    type="password" placeholder="Password" aria-describedby="passkey" name="passkey"
                                    value="<?php echo (!is_null($passkey['pass'])) ? $passkey['value'] : ''; ?>"
                                    required />
                                <div class="valid-feedback">
                                    Looks good.
                                </div>
                                <div class="invalid-feedback">
                                    A minimum of 15 character is requireds.<br>
                                    No "<" or ">" characters allowed. </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2 mb-5">

                        <div class="col-sm-12 text-center">

                            <button class="btn btn-sm btn-outline-primary" type="button"
                                onclick="grab('main_container','<?php echo $update; ?>','<?php echo $user; ?>', this.form.id);">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>