<form class="needs-validation" method="POST" action="" id="User" novalidate>

    <div class="container mt-3">

        <div class="row">
            <div class="col d-flex justify-content-center">
                <img loading="lazy" class="image rounded" width="230rem" height="200rem"
                    src="images/logo/c74bd95b8555275277d4e941c73985b4bcd923b36fcce75968ebb3c5a8d2b1ac411cfae4c2d473bff59a2b7b5ea220f0ac7bb8c880afb32f1b4881d59cc60d85.webp" />
            </div>
        </div>

        <div class="row mt-2">

            <div class="col d-flex justify-content-center">

                <h5 class="h5">Forgotten password form</h5>

            </div>
        </div>

        <div class="row mt-2">

            <div class="col d-flex justify-content-center">

                <p class="text-center">Submit your email address.<br>A confirmation email will be sent.<br>You can then
                    update your password.</p>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-sm-12 col-md-6 justify-content-center m-auto">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                    <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Email Address</span></legend>

                    <input minLength="10"
                        class="w-100 p-2 form-control required <?php echo (!is_null($email['message'])) ? $email['message'] : ''; ?>"
                        type="email" placeholder="Email Address" aria-describedby="email" name="email"
                        value="<?php echo (!is_null($email['value'])) ? $email['value'] : ''; ?>" required />
                    <div class="valid-feedback">
                        Looks good.
                    </div>
                    <div class="invalid-feedback">
                        A minimum of 10 characters and a valid domain name is required.
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row pt-3">

            <div class="col d-flex justify-content-center">

                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('init','<?php echo $calls['forgot']; ?>','<?php echo $calls['User']; ?>', this.form.id);">Submit</button>
            </div>
        </div>
    </div>
</form>