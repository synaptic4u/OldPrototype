<?php //  Shift for when only loading user's own journal
$userid = array_shift($data);
$name = '';

foreach ($data as $entry) {
    //  Shared journal load
    if (isset($entry['userid'])) {
        $userid = $entry['userid'];
        $name = $entry['name'];
    } ?>

    <div class="row mt-2 fading">
        
        <div class="col-sm-12">

            <a 
                class="text-decoration-none btn btn-outline-light hover w-100 px-1 py-1" 
                onclick="send('main_container','<?php echo $calls['show']; ?>','<?php echo $calls['Journal']; ?>', ['<?php echo $userid; ?>','<?php echo $entry['journalid']; ?>']);"
            >

                <fieldset class="border rounded pl-3 pr-3 pb-3 pt-1">

                    <legend class="w-auto">
                        <span class="pl-2 pr-2 h6">Journal of <?php echo $name; ?> <?php echo substr($entry['datedon'], 2, 14); ?></span>
                    </legend>
                    
                    <textarea 
                        class="form-control border rounded" 
                        rows="4"
                        disabled><?php echo $entry['events']; ?></textarea>
                </fieldset>
            </a>
        </div>
    </div>

<?php
} ?>