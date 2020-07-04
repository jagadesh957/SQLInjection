<h2><?php echo __('Edit block', 'blocks'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php
    if ($content !== null) {

        if (isset($errors['blocks_empty_name']) or isset($errors['blocks_exists'])) $error_class = 'error'; else $error_class = '';

        echo (Form::open(null, array('class' => 'form-horizontal')));

        echo (Form::hidden('blocks_old_name', Request::get('filename')));

?>

<div class="control-group <?php echo $error_class; ?>">
    <?php echo (Form::label('name', __('Name', 'blocks'), array('class' => 'control-label'))); ?>
    <div class="controls">
        <?php echo (Form::input('name', $name, array('class' => 'span5'))); ?>

        <?php
            if (isset($errors['blocks_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['blocks_empty_name'].'</span>';
            if (isset($errors['blocks_exists'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['blocks_exists'].'</span>';
        ?>
    </div>
</div>
<br />
<?php
        
        Action::run('admin_editor', array(Html::toText($content)));

        echo (
           Html::br().
           Form::submit('edit_blocks_and_exit', __('Save and exit', 'blocks'), array('class' => 'btn default')).Html::nbsp(2).
           Form::submit('edit_blocks', __('Save', 'blocks'), array('class' => 'btn default')). Html::nbsp().
           Form::close()
        );
        
    } else {
        echo '<div class="message-error">'.__('This block does not exist', 'blocks').'</div>';
    }
?>