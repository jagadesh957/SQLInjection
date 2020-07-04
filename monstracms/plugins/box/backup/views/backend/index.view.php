<h2><?php echo __('Backups', 'backup'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php
    echo (
        Form::open() .
        Form::checkbox('add_storage_folder', null, true, array('disabled' => 'disabled')) . ' ' . __('storage', 'backup') . ' ' . Html::nbsp(2) .
        Form::checkbox('add_public_folder') . ' ' . __('public', 'backup') . ' ' . Html::nbsp(2) .
        Form::checkbox('add_plugins_folder') . ' ' . __('plugins', 'backup') . ' ' . Html::nbsp(2) .         
        Form::submit('create_backup', __('Create backup', 'backup'), array('class' => 'btn default btn-small')).     
        Form::close() 
    );
?>  


<!-- Backup_list -->
<table class="table table-bordered">
    <thead>
        <tr>
            <td><?php echo __('Backup date', 'backup'); ?></td>
            <td><?php echo __('Size', 'backup'); ?></td>
            <td width="30%"><?php echo __('Actions', 'backup'); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php if (count($backups_list) > 0) rsort($backups_list); foreach ($backups_list as $backup) { ?>
    <tr>
        <td>
            <?php $name = strtotime(str_replace('-', '', basename($backup, '.zip'))); ?>
            <?php echo Html::anchor(Date::format($name, 'F jS, Y - g:i A'), Option::get('siteurl').'admin/index.php?id=backup&download='.$backup); ?>
    	</td>
        <td><?php echo Number::byteFormat(filesize(ROOT . DS . 'backups' . DS . $backup)); ?></td>
    	<td>
            <?php echo Html::anchor(__('Delete', 'backup'),
                      'index.php?id=system&sub_id=backup&delete_file='.$backup,
                       array('class' => 'btn btn-actions', 'onclick' => "return confirmDelete('".__('Delete backup: :backup', 'backups', array(':backup' => Date::format($name, 'F jS, Y - g:i A')))."')"));
             ?>
    	</td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<!-- /Backup_list -->