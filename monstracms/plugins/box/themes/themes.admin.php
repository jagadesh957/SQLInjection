<?php

    Navigation::add(__('Themes', 'themes'), 'extends', 'themes', 2);


    class ThemesAdmin extends Backend {


        /**
         * Themes plugin admin
         */
        public static function main() {
            
            // Get current theme
            $current_theme = Option::get('theme_name');

            // Init vsrs
            $themes    = Themes::getThemes();
            $templates = Themes::getTemplates();
            $chunks    = Themes::getChunks();
            $styles    = Themes::getStyles();
            $errors    = array();

            $chunk_path     = THEMES . DS . $current_theme . DS;
            $template_path  = THEMES . DS . $current_theme . DS;
            $style_path     = THEMES . DS . $current_theme . DS . 'css' . DS;


            // Save theme name
            if(Request::post('save_theme')) {
                Option::update('theme_name', Request::post('themes'));

                // Cleanup minify                               
                if (count($files = File::scan(MINIFY, array('css', 'js', 'php'))) > 0) foreach ($files as $file) File::delete(MINIFY . DS . $file);

                Request::redirect('index.php?id=themes');
            }

            // Its mean that you can add your own actions for this plugin
            Action::run('admin_themes_extra_actions');

            // Check for get actions
            // -------------------------------------  
            if (Request::get('action')) {

                // Switch actions
                // -------------------------------------  
                switch (Request::get('action')) {

                    // Add chunk
                    // -------------------------------------  
                    case "add_chunk":
                        if (Request::post('add_chunk') || Request::post('add_chunk_and_exit')) {
                          
                            if (trim(Request::post('name')) == '') $errors['chunk_empty_name'] = __('This field should not be empty', 'themes');
                            if (file_exists($chunk_path.Security::safeName(Request::post('name')).'.chunk.php')) $errors['chunk_exists'] = __('This chunk already exists', 'themes');

                            if (count($errors) == 0) {
                          
                                // Save chunk
                                File::setContent($chunk_path.Security::safeName(Request::post('name')).'.chunk.php', Request::post('content'));
                                
                                Notification::set('success', __('Your changes to the chunk <i>:name</i> have been saved.', 'themes', array(':name' => Security::safeName(Request::post('name')))));

                                if (Request::post('add_chunk_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_chunk&filename='.Security::safeName(Request::post('name')));                                    
                                }                                  
                            }
                        }

                        // Save fields
                        if (Request::post('name')) $name = Request::post('name'); else $name = '';
                        if (Request::post('content')) $content = Request::post('content'); else $content = '';

                        // Display view
                        View::factory('box/themes/views/backend/chunks/add')
                                ->assign('name', $name)
                                ->assign('content', $content)
                                ->assign('errors', $errors)
                                ->display();
                    break;

                    // Add template
                    // -------------------------------------  
                    case "add_template":
                         if (Request::post('add_template') || Request::post('add_template_and_exit')) {
                          
                            if (trim(Request::post('name')) == '') $errors['template_empty_name'] = __('This field should not be empty', 'themes');
                            if (file_exists($template_path.Security::safeName(Request::post('name')).'.template.php')) $errors['template_exists'] = __('This template already exists', 'themes');

                            if (count($errors) == 0) {
                          
                                // Save chunk
                                File::setContent($template_path.Security::safeName(Request::post('name')).'.template.php', Request::post('content'));
                                
                                Notification::set('success', __('Your changes to the chunk <i>:name</i> have been saved.', 'themes', array(':name' => Security::safeName(Request::post('name')))));

                                if (Request::post('add_template_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_template&filename='.Security::safeName(Request::post('name')));                                    
                                }                                  
                            }
                        }

                        // Save fields
                        if (Request::post('name')) $name = Request::post('name'); else $name = '';
                        if (Request::post('content')) $content = Request::post('content'); else $content = '';

                        // Display view
                        View::factory('box/themes/views/backend/templates/add')
                                ->assign('name', $name)
                                ->assign('content', $content)
                                ->assign('errors', $errors)
                                ->display();
                    break;

                    // Add styles
                    // -------------------------------------  
                    case "add_styles":
                         if (Request::post('add_styles') || Request::post('add_styles_and_exit')) {
                          
                            if (trim(Request::post('name')) == '') $errors['styles_empty_name'] = __('This field should not be empty', 'themes');
                            if (file_exists($style_path.Security::safeName(Request::post('name')).'.css')) $errors['styles_exists'] = __('This styles already exists', 'themes');

                            if (count($errors) == 0) {
                          
                                // Save chunk
                                File::setContent($style_path.Security::safeName(Request::post('name')).'.css', Request::post('content'));
                                
                                Notification::set('success', __('Your changes to the styles <i>:name</i> have been saved.', 'themes', array(':name' => Security::safeName(Request::post('name')))));

                                if (Request::post('add_styles_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_styles&filename='.Security::safeName(Request::post('name')));                                    
                                }                                  
                            }
                        }

                        // Save fields
                        if (Request::post('name')) $name = Request::post('name'); else $name = '';
                        if (Request::post('content')) $content = Request::post('content'); else $content = '';

                        // Display view
                        View::factory('box/themes/views/backend/styles/add')
                                ->assign('name', $name)
                                ->assign('content', $content)
                                ->assign('errors', $errors)
                                ->display();
                    break;

                    // Edit chunk
                    // -------------------------------------  
                    case "edit_chunk":

                        // Save current chunk action                                        
                        if (Request::post('edit_chunk') || Request::post('edit_chunk_and_exit') ) {                                                    
                            if (trim(Request::post('name')) == '') $errors['chunk_empty_name'] = __('This field should not be empty', 'themes');
                            if ((file_exists($chunk_path.Security::safeName(Request::post('name')).'.chunk.php')) and (Security::safeName(Request::post('chunk_old_name')) !== Security::safeName(Request::post('name')))) $errors['chunk_exists'] = __('This chunk already exists', 'themes');

                            // Save fields
                            if (Request::post('content')) $content = Request::post('content'); else $content = '';
                            if (count($errors) == 0) {

                                $chunk_old_filename = $chunk_path.Request::post('chunk_old_name').'.chunk.php';
                                $chunk_new_filename = $chunk_path.Security::safeName(Request::post('name')).'.chunk.php';
                                if ( ! empty($chunk_old_filename)) {
                                    if ($chunk_old_filename !== $chunk_new_filename) {
                                        rename($chunk_old_filename, $chunk_new_filename);
                                        $save_filename = $chunk_new_filename;
                                    } else {
                                        $save_filename = $chunk_new_filename;
                                    }                            
                                } else {
                                    $save_filename = $chunk_new_filename;
                                }

                                // Save chunk
                                File::setContent($save_filename, Request::post('content'));

                                Notification::set('success', __('Your changes to the chunk <i>:name</i> have been saved.', 'themes', array(':name' => basename($save_filename, '.chunk.php'))));

                                if (Request::post('edit_chunk_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_chunk&filename='.Security::safeName(Request::post('name')));                                    
                                }                        
                            }            
                        }
                        if (Request::post('name')) $name = Request::post('name'); else $name = File::name(Request::get('filename'));
                        $content = File::getContent($chunk_path.Request::get('filename').'.chunk.php');                    
                        
                        // Display view
                        View::factory('box/themes/views/backend/chunks/edit')
                                ->assign('content', $content)
                                ->assign('name', $name)
                                ->assign('errors', $errors)
                                ->display();
                                
                    break;

                    // Edit template
                    // -------------------------------------  
                    case "edit_template":

                        // Save current chunk action                                        
                        if (Request::post('edit_template') || Request::post('edit_template_and_exit') ) {                                                    
                            if (trim(Request::post('name')) == '') $errors['template_empty_name'] = __('This field should not be empty', 'themes');
                            if ((file_exists($template_path.Security::safeName(Request::post('name')).'.template.php')) and (Security::safeName(Request::post('template_old_name')) !== Security::safeName(Request::post('name')))) $errors['template_exists'] = __('This template already exists', 'themes');

                            // Save fields
                            if (Request::post('content')) $content = Request::post('content'); else $content = '';
                            if (count($errors) == 0) {

                                $template_old_filename = $template_path.Request::post('template_old_name').'.template.php';
                                $template_new_filename = $template_path.Security::safeName(Request::post('name')).'.template.php';
                                if ( ! empty($template_old_filename)) {
                                    if ($template_old_filename !== $template_new_filename) {
                                        rename($template_old_filename, $template_new_filename);
                                        $save_filename = $template_new_filename;
                                    } else {
                                        $save_filename = $template_new_filename;
                                    }                            
                                } else {
                                    $save_filename = $template_new_filename;
                                }

                                // Save chunk
                                File::setContent($save_filename, Request::post('content'));

                                Notification::set('success', __('Your changes to the template <i>:name</i> have been saved.', 'themes', array(':name' => basename($save_filename, '.template.php'))));

                                if (Request::post('edit_template_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_template&filename='.Security::safeName(Request::post('name')));                                    
                                }                        
                            }            
                        }
                        if (Request::post('name')) $name = Request::post('name'); else $name = File::name(Request::get('filename'));
                        $content = File::getContent($chunk_path.Request::get('filename').'.template.php');                    
                        
                        // Display view
                        View::factory('box/themes/views/backend/templates/edit')
                                ->assign('content', $content)
                                ->assign('name', $name)
                                ->assign('errors', $errors)
                                ->display();
                                
                    break;

                    // Edit styles
                    // -------------------------------------  
                    case "edit_styles":

                        // Save current chunk action                                        
                        if (Request::post('edit_styles') || Request::post('edit_styles_and_exit') ) {                                                    
                            if (trim(Request::post('name')) == '') $errors['styles_empty_name'] = __('This field should not be empty', 'themes');
                            if ((file_exists($style_path.Security::safeName(Request::post('name')).'.css')) and (Security::safeName(Request::post('styles_old_name')) !== Security::safeName(Request::post('name')))) $errors['styles_exists'] = __('This styles already exists', 'themes');

                            // Save fields
                            if (Request::post('content')) $content = Request::post('content'); else $content = '';
                            if (count($errors) == 0) {

                                $styles_old_filename = $style_path.Request::post('styles_old_name').'.css';
                                $styles_new_filename = $style_path.Security::safeName(Request::post('name')).'.css';
                                if ( ! empty($styles_old_filename)) {
                                    if ($styles_old_filename !== $styles_new_filename) {
                                        rename($styles_old_filename, $styles_new_filename);
                                        $save_filename = $styles_new_filename;
                                    } else {
                                        $save_filename = $styles_new_filename;
                                    }                            
                                } else {
                                    $save_filename = $styles_new_filename;
                                }

                                // Save chunk
                                File::setContent($save_filename, Request::post('content'));

                                Notification::set('success', __('Your changes to the styles <i>:name</i> have been saved.', 'themes', array(':name' => basename($save_filename, '.css'))));

                                if (Request::post('edit_styles_and_exit')) {
                                    Request::redirect('index.php?id=themes');    
                                } else {
                                    Request::redirect('index.php?id=themes&action=edit_styles&filename='.Security::safeName(Request::post('name')));                                    
                                }                        
                            }            
                        }
                        if (Request::post('name')) $name = Request::post('name'); else $name = File::name(Request::get('filename'));
                        $content = File::getContent($style_path.Request::get('filename').'.css');                    
                        
                        // Display view
                        View::factory('box/themes/views/backend/styles/edit')
                                ->assign('content', $content)
                                ->assign('name', $name)
                                ->assign('errors', $errors)
                                ->display();
                                
                    break;

                    // Delete chunk
                    // -------------------------------------  
                    case "delete_chunk":
                        File::delete($chunk_path.Request::get('filename').'.chunk.php');
                        Notification::set('success', __('Chunk <i>:name</i> deleted', 'themes', array(':name' => File::name(Request::get('filename')))));
                        Request::redirect('index.php?id=themes');
                    break;


                    // Delete styles
                    // -------------------------------------  
                    case "delete_styles":
                        File::delete($style_path.Request::get('filename').'.css');
                        Notification::set('success', __('Styles <i>:name</i> deleted', 'themes', array(':name' => File::name(Request::get('filename')))));
                        Request::redirect('index.php?id=themes');
                    break;

                    // Delete template
                    // -------------------------------------  
                    case "delete_template":
                        File::delete($template_path.Request::get('filename').'.template.php');
                        Notification::set('success', __('Template <i>:name</i> deleted', 'themes', array(':name' => File::name(Request::get('filename')))));
                        Request::redirect('index.php?id=themes');
                    break;

                    // Clone styles
                    // ------------------------------------- 
                    case "clone_styles":
                        File::setContent(THEMES . DS . $current_theme . DS . 'css' . DS . Request::get('filename') .'_clone_'.date("Ymd_His").'.css',
                                         File::getContent(THEMES . DS . $current_theme . DS . 'css' . DS . Request::get('filename') . '.css'));
                                         
                        Request::redirect('index.php?id=themes');
                    break;

                    // Clone template
                    // ------------------------------------- 
                    case "clone_template":
                        File::setContent(THEMES . DS . $current_theme . DS . Request::get('filename') .'_clone_'.date("Ymd_His").'.template.php',
                                         File::getContent(THEMES . DS . $current_theme . DS . Request::get('filename') . '.template.php'));
                                         
                        Request::redirect('index.php?id=themes');
                    break;

                    // Clone chunk
                    // ------------------------------------- 
                    case "clone_chunk":
                        File::setContent(THEMES . DS . $current_theme . DS . Request::get('filename') .'_clone_'.date("Ymd_His").'.chunk.php',
                                         File::getContent(THEMES . DS . $current_theme . DS . Request::get('filename') . '.chunk.php'));
                                         
                        Request::redirect('index.php?id=themes');
                    break;
                    
                }
                
            } else {
                
                // Display view
                View::factory('box/themes/views/backend/index')
                        ->assign('themes', $themes)
                        ->assign('templates', $templates)
                        ->assign('chunks', $chunks)
                        ->assign('styles', $styles)
                        ->assign('current_theme', $current_theme)
                        ->display();

            }
        }
    }
