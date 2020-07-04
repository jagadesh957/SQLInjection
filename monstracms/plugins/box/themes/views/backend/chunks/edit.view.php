<h2><?php echo __('Edit chunk', 'themes'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php
    if ($content !== null) { 

        if (isset($errors['chunk_empty_name']) or isset($errors['chunk_exists'])) $error_class = 'error'; else $error_class = '';

        echo (Form::open(null, array('class' => 'form-horizontal')));

        echo (Form::hidden('chunk_old_name', Request::get('filename')));

?>

<div class="control-group <?php echo $error_class; ?>">
    <?php echo (Form::label('name', __('Name', 'themes'), array('class' => 'control-label'))); ?>
    <div class="controls">
        <div class="input-append">
            <?php echo (Form::input('name', $name, array('class' => 'span5'))); ?><span class="add-on">.chunk.php</span>
        </div>

        <?php
            if (isset($errors['chunk_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['chunk_empty_name'].'</span>';
            if (isset($errors['chunk_exists'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['chunk_exists'].'</span>';
        ?>
    </div>
</div>

<?php

        echo (
           Html::br().
           Form::label('content', __('Chunk content', 'themes')).
           Form::textarea('content', Html::toText($content), array('style' => 'width:100%;height:400px;', 'class' => 'source-editor')).
           Html::br(2).
           Form::submit('edit_chunk_and_exit', __('Save and exit', 'themes'), array('class' => 'btn default')).Html::nbsp(2).
           Form::submit('edit_chunk', __('Save'), array('class' => 'btn default')). Html::nbsp().
           Form::close()
        );
        
    } else {
        echo '<div class="message-error">'.__('This chunk does not exist', 'themes').'</div>';
    }
?>