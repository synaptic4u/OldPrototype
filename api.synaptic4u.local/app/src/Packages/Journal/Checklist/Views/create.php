<form method="POST" action="" id="Checklist" class="fading">

    <div class="container-flex mt-1" id="">

        <div class="row justify-content-center">

            <div class="col-sm-12">

                <div class="container">

                    <div class="row">

                        <div class="col-sm-12 text-center">

                            <h5 class="h5">Create your daily checklist..!</h5>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none">

                                    <span class="ps-2 pe-2 h6">Daily Routine</span>
                                </legend>

                                <textarea class="form-control border rounded" type="text" name="list"
                                    placeholder="Add your routine checklist here." rows="4" required></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-sm-12">

                            <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                                <legend class="w-auto float-none">

                                    <span class="ps-2 pe-2 h6">Daily Values</span>
                                </legend>

                                <textarea class="form-control border rounded" type="text" name="quotes"
                                    placeholder="Add your core daily values / beliefs that you would like to build into your life."
                                    rows="4" required></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row mt-2 mb-5">

                        <div class="col-sm-12 text-center">

                            <button class="btn btn-sm btn-outline-primary" type="button"
                                onclick="grab('main_container','<?php echo $calls['store']; ?>','<?php echo $calls['Checklist']; ?>', this.form.id);">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>