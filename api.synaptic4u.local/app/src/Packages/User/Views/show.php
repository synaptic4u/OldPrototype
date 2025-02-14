<div class="container-flex mt-3 fading" id="User">
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
                        <div class="form-row justify-content-md-end d-grid gap-2 d-md-block align-self-bottom mr-md-2">
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                onclick="send('main_container','<?php echo $edit; ?>','<?php echo $user; ?>', ['<?php echo $userid; ?>']);">Edit
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12">
                        <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                            <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">First Name</span></legend>
                            <p><?php echo $firstname['value']; ?></p>
                        </fieldset>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12">
                        <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                            <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Surname</span></legend>
                            <p><?php echo $surname['value']; ?></p>
                        </fieldset>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12">
                        <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                            <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Email Address</span></legend>
                            <p><?php echo $email['value']; ?></p>
                        </fieldset>
                    </div>
                </div>

                <div class="row mt-2 mb-5">
                    <div class="col-sm-12">
                        <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">
                            <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Contact Number</span></legend>
                            <p><?php echo $contactnu['value']; ?></p>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>