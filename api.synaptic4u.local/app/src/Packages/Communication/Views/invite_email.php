<html>
    <body>
        <div rules="all" style="border:1px solid grey;border-radius:5px; width:750px;">
            <div>
                <h3 style="width:650px;margin:auto;padding-top:1em;"><?php echo $to_user; ?> please accept the invitation.</h3>
                <p>
                    <?php echo $from_user; ?> has invited you to be a part of <?php echo $hierachyname; ?><br>
                    Upon registration you will have access to all applications that <?php echo $hierachyname; ?> has given you.<br>
                    This link will remain valid for 24 hours.
                </p>
                <p>
                    If you do not know <?php echo $from_user; ?> or <?php echo $hierachyname; ?> and do not want to recieve future invites;<br>
                    Please email us on <a href="mailto:system@synaptic4u.co.za">system@synaptic4u.co.za</a>    
                </p>
            </div>
            
            <div>
                <a href="<?php echo $url; ?>" style="text-decoration:none;">
                    <h4 style="width:250px;margin:auto;padding-top:1em;">Confirm invitation</h4>
                </a>
            </div>

            <div>
                <h4 style="width:250px;margin:auto;padding-top:1em;padding-bottom:1em;">Thank you</h4>
            </div>
        </div>
    </body>
</html>