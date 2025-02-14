<form class="container-fluid fading" id="ConfigJournal">

    <div class="container-flex mt-3">

        <div class="row">

            <div class="col-12 text-center">

                <h5 class="h5">Add your journal section</h5>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-12">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                    <legend class="w-auto float-none">

                        <span class="ps-2 pe-2 h6">Title</span>
                    </legend>

                    <input class="w-100 p-2" type="text" name="title" placeholder="Title" aria-describedby="title"
                        required />
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">

            <div class="col-12 text-center">

                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('dash_show','<?php echo $calls['store']; ?>','<?php echo $calls['ConfigJournal1']; ?>', this.form.id);send('dash_show','<?php echo $calls['loadlist']; ?>','<?php echo $calls['ConfigJournal2']; ?>');">Create</button>
            </div>
        </div>
    </div>
</form>