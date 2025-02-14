<?php foreach ($usersArray as $key => $value) { ?>
    <tr id="rolestoggle<?php echo $key; ?>">
        <td class="align-middle text-nowrap"><?php echo $value['user']['value']; ?></td>
        <td class="align-middle text-nowrap"><?php echo $value['email']['value']; ?></td>
        <td class="align-middle text-nowrap"><?php echo substr($value['updatedon']['value'], 0, 10); ?></td>
        <td class="align-middle text-nowrap"><?php echo $value['role']['value']; ?></td>
        <?php if((int)$canedit === 1){ ?>
        <td class="align-middle text-nowrap">
            <button class="btn btn-sm btn-outline-secondary m-0" type="button" onclick="send('<?php echo $users_for_form_id; ?>','<?php echo $edit; ?>','<?php echo $users; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'userid' : '<?php echo $value['userid']['value']; ?>'});">Edit</button>                   
        </td>
        <?php } ?>
    </tr>
<?php } ?>