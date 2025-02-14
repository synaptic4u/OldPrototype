<form class="container-fluid fading" id="ConfigJournal">

    <input type="hidden" name="param" value="<?php echo $data['sectionid']; ?>">

    <div class="container-flex mt-3">

        <div class="row">

            <div class="col-12 text-center">

                <h5 class="h5">Edit your journal section</h5>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-12">

                <fieldset class="border rounded ps-3 pe-3 pb-3 pt-1">

                    <legend class="w-auto float-none"><span class="ps-2 pe-2 h6">Title created on
                            <?php echo substr($data['datedon'], 2, 8); ?></span></legend>

                    <input class="form-control border rounded" nmae="title" type="text" placeholder="Title"
                        aria-describedby="title" value="<?php echo $data['title']; ?>" required />
                </fieldset>
            </div>
        </div>

        <div class="row mt-2 mb-2">

            <div class="col-12 text-center">

                <button class="btn btn-sm btn-outline-primary" type="button"
                    onclick="grab('dash_show','<?php echo $calls['update']; ?>','<?php echo $calls['ConfigJournal1']; ?>', this.form.id);send('dash_show','<?php echo $calls['loadlist']; ?>','<?php echo $calls['ConfigJournal2']; ?>');">Update</button>
            </div>
        </div>
    </div>
</form>