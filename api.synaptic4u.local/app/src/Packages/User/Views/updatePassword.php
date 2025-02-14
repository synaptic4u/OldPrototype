<form class="needs-validation" method="POST" action="" id="User" novalidate>

    <input type="hidden" name="param" value="<?php echo $calls['userid']; ?>" />

    <div class="container mt-3">

        <div class="row">
            <div class="col d-flex justify-content-center">
                <img loading="lazy" class="image rounded" src="images/logo/logo.webp" width="230rem" height="200rem" />
            </div>
        </div>

        <div class="row mt-2">

            <div class="col d-flex justify-content-center">

                <h5 class="h5">Please add your new password.</h5>

            </div>
        </div>

        <div class="row mt-0">

            <div class="col d-flex justify-content-center">

                <p class="text-center">Upon submission you will receive a confirmation email to login.</p>
            </div>
        </div>
        
        <div class="row mt-1">

            <div class="col-sm-12 col-md-6 justify-content-center m-auto">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">

                    <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Password</span></legend>

                    <input minLength="15"
                        class="w-100 p-2 form-control required <?php echo (!is_null($password['message'])) ? $password['message'] : ''; ?>"
                        type="password" placeholder="Password" aria-describedby="passkey" name="passkey"
                        id="login_password"
                        value="<?php echo (!is_null($password['value'])) ? $password['value'] : ''; ?>" required />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        A minimum of 15 character is required.<br>
                        No "<" or ">" characters allowed. </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-sm-12 col-md-6 justify-content-center m-auto">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-0">

                    <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Password</span></legend>

                    <input minLength="15"
                        class="w-100 p-2 form-control required <?php echo (!is_null($password['message'])) ? $password['message'] : ''; ?>"
                        type="password" placeholder="Password" aria-describedby="passkeychk" name="passkeychk"
                        id="login_password_verify"
                        value="<?php echo (!is_null($password['value'])) ? $password['value'] : ''; ?>" required />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        A minimum of 15 character is required.<br>
                        No "<" or ">" characters allowed. </div>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2" style="display: none;" id="warning">

            <div class="col-sm-12 justify-content-center m-auto">

                <span class="alert alert-danger">Please make sure the passwords match.<br>You cannot submit short
                    passwords.</span>
            </div>
        </div>

        <div class="row pt-3 mb-2">

            <div class="col d-flex justify-content-center">

                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="verify(this.form.id);">Submit</button>
            </div>
        </div>
    </div>
</form>

<!-- SYSTEM PARAMS CONTAINER updated on each call -->
<div id="system_params" style="display: none;">
</div>