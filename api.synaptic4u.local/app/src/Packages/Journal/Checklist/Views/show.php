<div class="container-flex mt-1 fading">

    <form method="POST" action="" id="Checklist">

        <div class="row">

            <div class="col-md-2 col-sm-0">
            </div>

            <div class="col-md-8 col-sm-12 text-center">

                <h5 class="h5 mt-1 mb-0">Your Checklist from <?php echo substr($data['datedon'], 2, 8); ?></h5>
            </div>

            <div class="col-md-2 col-sm-12 pr-md-4">

                <div class="form-row justify-content-md-end justify-content-center align-self-bottom mr-md-2">

                    <button class="btn btn-sm btn-outline-secondary" type="button"
                        onclick="send('main_container','<?php echo $calls['edit']; ?>','<?php echo $calls['Checklist']; ?>', ['<?php echo $data['checklistid']; ?>']);">Edit</button>
                </div>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-sm-12">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                    <legend class="w-auto float-none">

                        <span class="ps-2 pe-2 h6">Daily Routine</span>
                    </legend>

                    <textarea class="form-control border rounded" type="text" rows="4"
                        disabled><?php echo $data['list']; ?></textarea>
                </fieldset>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-sm-12">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                    <legend class="w-auto float-none">

                        <span class="ps-2 pe-2 h6">Daily Values</span>
                    </legend>

                    <textarea class="form-control border rounded" type="text" rows="4"
                        disabled><?php echo $data['qoutes']; ?></textarea>
                </fieldset>
            </div>
        </div>
    </form>
</div>