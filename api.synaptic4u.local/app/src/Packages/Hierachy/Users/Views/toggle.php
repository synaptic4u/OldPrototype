
    <td class="align-middle"><?php echo $role['value']; ?></td>
    <td class="align-middle"><?php echo substr($datedon, 0, 10); ?></td>
    <td class="align-middle"><?php echo $user; ?></td>
    <td class="align-middle">
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox" 
                name="exclude" 
                id="exclude" 
                href="#exclude"
                role="button" 
                onclick="send('<?php echo $toggleid; ?>','<?php echo $toggle; ?>','<?php echo $roles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'roleid' : '<?php echo $roleid['value']; ?>', 'exclude' : '<?php echo $exclude; ?>', 'toggleid' : '<?php echo $toggleid; ?>'});"
                aria-controls="exclude" <?php echo ((int) $exclude === 0) ? ' checked' : ''; ?>>
            <label 
                class="form-check-label" 
                for="exclude" 
                data-bs-toggle="tooltip"
                data-bs-html="true"
                title="Enables or disables the type available for selection."><?php echo ((int) $exclude === 0) ? 'Enabled' : 'Disabled'; ?></label>
        </div>
    </td>
    <td class="align-middle">
        <?php if((int)$userid === 3){ ?>
            <span class="text-primary">System</span>
        <?php }else{ ?>
            <button 
                class="btn btn-sm btn-outline-secondary m-0" 
                type="button" 
                onclick="send('<?php echo $roles_for_form_id; ?>','<?php echo $edit; ?>','<?php echo $roles; ?>', {'hierachyid' : '<?php echo $hierachyid['value']; ?>', 'detid' : '<?php echo $detid['value']; ?>', 'roleid' : '<?php echo $roleid['value']; ?>'});">Edit</button>
        <?php } ?>
        
    </td>
