<h2><?php echo __('New snippet', 'snippets'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php if (isset($errors['snippets_empty_name']) or isset($errors['snippets_exists'])) $error_class = 'error'; else $error_class = ''; ?>


<?php echo (Form::open(null, array('class' => 'form-horizontal'))); ?>

<div class="control-group <?php echo $error_class; ?>">
    <?php echo (Form::label('name', __('Name', 'snippets'), array('class' => 'control-label'))); ?>
    <div class="controls">
        <div class="input-append">
            <?php echo (Form::input('name', $name, array('class' => 'span5'))); ?><span class="add-on">.snippet.php</span>
        </div>

        <?php
            if (isset($errors['snippets_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['snippets_empty_name'].'</span>';
            if (isset($errors['snippets_exists'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['snippets_exists'].'</span>';
        ?>
    </div>
</div>

<?php
    echo (
       Html::br().
       Form::label('content', __('Snippet content', 'snippets')).
       Form::textarea('content', $content, array('style' => 'width:100%;height:400px;', 'class'=>'source-editor')).
       Html::br(2).
       Form::submit('add_snippets_and_exit', __('Save and exit', 'snippets'), array('class' => 'btn')).Html::nbsp(2).
       Form::submit('add_snippets', __('Save', 'snippets'), array('class' => 'btn')).
       Form::close()
    );

?>
