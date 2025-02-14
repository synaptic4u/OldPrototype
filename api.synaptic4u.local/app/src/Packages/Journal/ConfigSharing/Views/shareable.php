<div class="container mx-1 px-1 fading">

    <div class="row no-gutters">

        <div id="config_sharing" class="col-12 mx-0">

            <form method="POST" class="container-fluid px-0" id="ConfigSharing">

                <div class="container-flex mt-3">

                    <div class="row">

                        <div class="col-12 text-center">

                            <h5 class="h5">Configure your sharing visibility</h5>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none">

                                    <span class="ps-2 pe-2 h6">Enable / Disable sharing</span>
                                </legend>

                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="shareableRadio"
                                        id="shareableRadioEnable" value="1" <?php echo $enable; ?>>

                                    <label class="form-check-label" for="shareableRadioEnable">
                                        Enable sharing
                                    </label>
                                </div>

                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="shareableRadio"
                                        id="shareableRadioDisable" value="0" <?php echo $disable; ?>>

                                    <label class="form-check-label" for="shareableRadioDisable">
                                        Disable sharing
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2 mb-2">

                        <div class="col-12 text-center">

                            <button class="btn btn-sm btn-outline-primary" type="button"
                                onclick="grab('config_sharing','<?php echo $calls['store']; ?>','<?php echo $calls['ConfigSharing1']; ?>', this.form.id);">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row no-gutters mt-2">

        <div id="config_shared" class="col-12 mx-0"></div>
    </div>
    <div class="row no-gutters mt-2">

        <div id="config_following" class="col-12 mx-0"></div>
    </div>
    <div class="row no-gutters mt-2">

        <div id="config_followed_by" class="col-12 mx-0"></div>
    </div>
</div>