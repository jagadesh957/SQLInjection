<?php

    // Check if is user is logged in then set variables for welcome button
    if (Session::exists('user_id')) {
        $user_id = Session::get('user_id');
        $user_login = Session::get('user_login');        
    } else {
        $user_id = '';
        $user_login = '';
    }

    Navigation::add(__('Users', 'users'), 'system', 'users', 2);

    
    class UsersAdmin extends Backend {

        /**
         * Users admin
         */
        public static function main() {

            // Users roles
            $roles = array('admin'  => __('Admin', 'users'),
                           'editor' => __('Editor', 'users'),
                           'user'   => __('User', 'users'));

            // Get uses table
            $users = new Table('users');

            // Check for get actions
            // ---------------------------------------------
            if (Request::get('action')) {

                // Switch actions
                // -----------------------------------------
                switch (Request::get('action')) {

                    // Add
                    // -------------------------------------   
                    case "add":

                        if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin'))) {

                            $errors = array();
                            if (Request::post('register')) {
                                $user_login = trim(Request::post('login'));
                                $user_password = trim(Request::post('password'));
                                if ($user_login == '')    $errors['users_empty_login']    = __('This field should not be empty', 'users');
                                if ($user_password == '') $errors['users_empty_password'] = __('This field should not be empty', 'users');
                                $user = $users->select("[login='".$user_login."']");
                                if ($user != null) $errors['users_this_user_alredy_exists'] = __('This user alredy exist', 'users');
                                
                                if (count($errors) == 0) {
                                    $users->insert(array('login'           => Security::safeName($user_login),
                                                         'password'        => Security::encryptPassword(Request::post('password')),
                                                         'email'           => Request::post('email'),
                                                         'date_registered' => time(),
                                                         'role'            => Request::post('role')));

                                    Notification::set('success', __('New user have been registered.', 'users')); 
                                    Request::redirect('index.php?id=users');
                                }

                            }
                            
                            // Display view
                            View::factory('box/users/views/backend/add')
                                    ->assign('roles', $roles)
                                    ->assign('errors', $errors)                                    
                                    ->display();

                        } else {
                            Request::redirect('index.php?id=users&action=edit&user_id='.Session::get('user_id'));
                        }

                    break;

                    // Edit
                    // -------------------------------------   
                    case "edit":

                        // Get current user record
                        $user = $users->select("[id='".(int)Request::get('user_id')."']", null);
                        
                        if (isset($user['firstname'])) $user_firstname = $user['firstname']; else $user_firstname = '';
                        if (isset($user['lastname']))  $user_lastname  = $user['lastname'];  else $user_lastname  = '';
                        if (isset($user['email']))     $user_email = $user['email']; else $user_email = '';
                        if (isset($user['twitter']))   $user_twitter = $user['twitter']; else $user_twitter = '';
                        if (isset($user['skype']))     $user_skype = $user['skype']; else $user_skype = '';

                        if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {

                            if ((Request::post('edit_profile')) and
                                 (((int)Session::get('user_id') == (int)Request::get('user_id')) or
                                   (in_array(Session::get('user_role'), array('admin'))))){

                                if (Security::safeName(Request::post('login')) != '') {                                       
                                    if ($users->update(Request::post('user_id'), array('login' => Security::safeName(Request::post('login')),
                                                                                  'firstname' => Request::post('firstname'),
                                                                                  'lastname'  => Request::post('lastname'),
                                                                                  'email'     => Request::post('email'),
                                                                                  'skype'     => Request::post('skype'),
                                                                                  'twitter'   => Request::post('twitter'),
                                                                                  'role'      => Request::post('role')))) { 
                                        
                                        Notification::set('success', __('Your changes have been saved.', 'users'));                                            
                                        Request::redirect('index.php?id=users&action=edit&user_id='.Request::post('user_id'));
                                    }
                                } else {
                                    
                                }  
                                
                            }

                            if (Request::post('edit_profile_password')) {
                                if (Security::encryptPassword(Request::post('old_password')) == Request::post('real_old_password')) {                           
                                    $users->update(Request::post('user_id'), array('password' => Security::encryptPassword(Request::post('new_password'))));
                                    Notification::set('success', __('Your changes have been saved.', 'users'));                                            
                                    Request::redirect('index.php?id=users&action=edit&user_id='.Request::post('user_id'));
                                } else {
                                    Notification::set('error', __('Wrong old password', 'users'));                                            
                                    Request::redirect('index.php?id=users&action=edit&user_id='.Request::post('user_id'));
                                }
                            }

                            if ( ((int)Session::get('user_id') == (int)Request::get('user_id')) or (in_array(Session::get('user_role'), array('admin')) && count($user) != 0) ) {

                                // Display view
                                View::factory('box/users/views/backend/edit')
                                        ->assign('user', $user)
                                        ->assign('user_firstname', $user_firstname)
                                        ->assign('user_lastname', $user_lastname)
                                        ->assign('user_email', $user_email)
                                        ->assign('user_twitter', $user_twitter)
                                        ->assign('user_skype', $user_skype)
                                        ->assign('roles', $roles)
                                        ->display();

                            } else {
                                echo 'Monstra says: This is not your profile...'; 
                            }
                        
                        }

                    break;
                    
                    // Delete
                    // -------------------------------------   
                    case "delete":

                        if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin'))) {
                            $user = $users->select('[id="'.Request::get('user_id').'"]', null);
                            $users->delete(Request::get('user_id'));
                            Notification::set('success', __('User <i>:user</i> have been deleted.', 'users', array(':user' => $user['login']))); 
                            Request::redirect('index.php?id=users');
                        }

                    break;
                }
            } else {

                if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin'))) {
                    
                    // Get all records from users table
                    $users_list = $users->select();
                    
                    // Dislay view
                    View::factory('box/users/views/backend/index')
                            ->assign('roles', $roles)
                            ->assign('users_list', $users_list)
                            ->display();

                } else {
                    Request::redirect('index.php?id=users&action=edit&user_id='.Session::get('user_id'));
                }
            }
            
        }
    }