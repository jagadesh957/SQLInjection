<?php  if (!defined('MONSTRA_ACCESS')) exit('No direct script access allowed'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Monstra :: <?php echo __('Administration', 'system'); ?></title>
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
        <meta name="description" content="Monstra admin area" />
        <link rel="icon" href="<?php echo Option::get('siteurl'); ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo Option::get('siteurl'); ?>favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo Option::get('siteurl'); ?>admin/themes/default/css/styles.css" media="all" type="text/css" />
        <script type="text/javascript" src="<?php echo Option::get('siteurl'); ?>public/assets/js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {

                <?php if (Notification::get('reset_password_error') == 'reset_password_error') { ?>
                    $('.reset-password-area').show();   
                    $('.administration-area').hide();
                    $('.reset-password-btn').hide();   
                    $('.administration-btn').show();                
                <?php } else { ?>
                    $('.reset-password-area').hide();
                    $('.administration-area').show();
                    $('.reset-password-btn').show();   
                    $('.administration-btn').hide();               
                <?php } ?>
                 
                $('.reset-password-btn').click(function() {
                    $('.reset-password-area').show();   
                    $('.administration-area').hide();
                    $('.reset-password-btn').hide();
                    $('.administration-btn').show();
                });

                $('.administration-btn').click(function() {
                    $('.reset-password-area').hide();   
                    $('.administration-area').show();
                    $('.reset-password-btn').show();
                    $('.administration-btn').hide();
                });
            });     
        </script>
    </head>
    <body class="login-body">
        
        <!-- Block_wrapper -->
        <div class="well authorization-block">
            <div style="text-align:center;"><a class="brand" href="#"><img src="<?php echo Option::get('siteurl'); ?>public/assets/img/monstra-logo-black.png"></a></div>
            <div class="administration-area">
            <hr>
            <div class="row">
              <div class="span4">
                    <h2 style="text-align:center;"><?php echo __('Administration', 'system'); ?></h2><br />
                    <form method="post">
                        <label><?php echo __('Login', 'users'); ?></label>
                        <input class="span4" name="login" type="text" />

                        <label><?php echo __('Password', 'users'); ?></label>
                        <input class="span4" name="password" type="password" />
                        <br />
                        <?php if (isset($login_error) && $login_error !== '') { ?><div class="alert alert-error"><?php echo $login_error; ?></div><?php } ?>
                        <input type="submit" name="login_submit" class="btn" value="<?php echo __('Enter', 'users')?>" />
                    </form>
              </div>
            </div>
            </div>
            
            <div class="reset-password-area">
            <hr>
            <div class="row">
                <div class="span4">
                        <h2 style="text-align:center;"><?php echo __('Reset Password', 'users'); ?></h2><br />
                        <form method="post">
                            <label><?php echo __('Login'); ?></label>
                            <input name="login" class="span4" type="text" />
                            <br />
                            <?php if (isset($reset_password_error) && $reset_password_error !== '') { ?><div class="alert alert-error"><?php echo $reset_password_error; ?></div><?php } ?>
                            <input type="submit" name="reset_password_submit" class="btn" value="<?php echo __('Send New Password', 'users')?>" />
                        </form>
                  </div>
              </div> 
            </div>  
            
            <hr>
            <div class="row">
                <div class="span4" style="text-align:center;">
                    <a class="small-grey-text" href="<?php echo Option::get('siteurl'); ?>"><?php echo __('< Back to Website', 'system');?></a> - 
                    <a class="small-grey-text reset-password-btn" href="javascript:;"><?php echo __('Forgot your password? >', 'system');?></a>
                    <a class="small-grey-text administration-btn" href="javascript:;"><?php echo __('Administration >', 'system');?></a>
                </div>
            </div>
        </div>

        <div class="authorization-block-footer">
            <div  style="text-align:center">
                <span class="small-grey-text">© 2012 <a href="http://monstra.org" class="small-grey-text" target="_blank">Monstra</a> – <?php echo __('Version', 'system'); ?> <?php echo MONSTRA_VERSION; ?></span>            
            </div>
        </div>
        <!-- /Block_wrapper -->
        
    </body>
</html>