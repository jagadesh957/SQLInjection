<h2><?php echo __('Users', 'users'); ?></h2>
<br />

<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>

<?php echo Html::anchor(__('Register new user', 'users'), 'index.php?id=users&action=add', array('title' => __('Create new page', 'users'), 'class' => 'btn default btn-small')); ?>

<br /><br />

<!-- Users_list -->
<table class="table table-bordered">
    <thead>
        <tr>
            <td><?php echo __('Login', 'users'); ?></td>
            <td><?php echo __('Email', 'users'); ?></td>
            <td><?php echo __('Registered', 'users'); ?></td>
            <td><?php echo __('Role', 'users'); ?></td>
            <td width="30%"><?php echo __('Actions', 'users'); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users_list as $user) { ?>
    <tr>        
        <td>
            <?php echo Html::toText($user['login']); ?>
        </td>
        <td>
            <?php echo Html::toText($user['email']); ?>
        </td>
        <td>
            <?php echo Date::format($user['date_registered']); ?>
        </td>
        <td>
            <?php echo $roles["{$user['role']}"]; ?>
        </td>
        <td>
            <?php echo Html::anchor(__('Edit', 'users'), 'index.php?id=users&action=edit&user_id='.$user['id'], array('class' => 'btn btn-actions')); ?>
            <?php echo Html::anchor(__('Delete', 'users'),
                       'index.php?id=users&action=delete&user_id='.$user['id'],
                       array('class' => 'btn btn-actions', 'onclick' => "return confirmDelete('".__('Delete user: :user', 'users', array(':user' => Html::toText($user['login'])))."')"));
             ?>
        </td>
    </tr> 
    <?php } ?>
    </tbody>
</table>
<!-- /Users_list -->