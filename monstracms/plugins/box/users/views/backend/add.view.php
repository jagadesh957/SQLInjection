<!-- Users_add -->
<?php
    echo ( '<h2>'.__('New User Registration', 'users').'</h2>' );

    echo (
        Html::br().
        Form::open().
        Form::label('login', __('Login', 'users')).
        Form::input('login', null, array('class' => 'span6'))
    );

    if (isset($errors['users_this_user_alredy_exists'])) echo Html::nbsp(3).'<span class="error">'.$errors['users_this_user_alredy_exists'].'</span>';
    if (isset($errors['users_empty_login'])) echo Html::nbsp(3).'<span class="error">'.$errors['users_empty_login'].'</span>';

    echo (
        Form::label('password', __('Password', 'users')).
        Form::password('password', null, array('class' => 'span6'))
    );

    if (isset($errors['users_empty_password'])) echo Html::nbsp(3).'<span class="error">'.$errors['users_empty_password'].'</span>';

    echo (
        Form::label('email', __('Email', 'users')).
        Form::input('email', null, array('class' => 'span6')). Html::br().
        Form::label('role', __('Role', 'users')).
        Form::select('role', array('admin' => __('Admin', 'users'), 'user' => __('User', 'users'),'editor' => __('Editor', 'users')), null, array('class' => 'span3')). Html::br(2).
        Form::submit('register', __('Register', 'users'), array('class' => 'btn default')).
        Form::close()
    );
?>
<!-- /Users_add -->