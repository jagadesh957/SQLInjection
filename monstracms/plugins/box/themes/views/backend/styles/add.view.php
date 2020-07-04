<h2><?php echo __('New styles', 'themes'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php if (isset($errors['styles_empty_name']) || isset($errors['styles_exists'])) $error_class = 'error'; else $error_class = ''; ?>

<?php echo (Form::open(null, array('class' => 'form-horizontal'))); ?>

<div class="control-group <?php echo $error_class; ?>">
    <?php echo (Form::label('name', __('Name', 'themes'), array('class' => 'control-label'))); ?>
    <div class="controls">
        <div class="input-append">
            <?php echo (Form::input('name', $name, array('class' => 'span5'))); ?><span class="add-on">.css</span>
        </div>

        <?php
            if (isset($errors['styles_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['styles_empty_name'].'</span>';
            if (isset($errors['styles_exists'])) echo '&nbsp;&nbsp;&nbsp;<span style="color:red">'.$errors['styles_exists'].'</span>';
        ?>
    </div>
</div>

<?php
    echo Html::br();
    echo Form::label('content', __('Styles content', 'themes'));
    echo Form::textarea('content', $content, array('style' => 'width:100%;height:400px;', 'class'=>'source-editor'));

    echo (
        Html::br(2).
        Form::submit('add_styles_and_exit', __('Save and exit', 'themes'), array('class' => 'btn')).Html::nbsp(2).
        Form::submit('add_styles', __('Save', 'themes'), array('class' => 'btn')).
        Form::close()
    );
?>