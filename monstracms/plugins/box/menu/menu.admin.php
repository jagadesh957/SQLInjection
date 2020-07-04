<?php

    // Add plugin navigation link
    Navigation::add(__('Menu', 'menu'), 'content', 'menu', 3);

    Action::add('admin_header', 'menuAdminHeaders');

    function menuAdminHeaders() {
        echo ("
            <script> 
                function addMenuPage(slug, title) {
                    $('input[name=menu_item_link]').val(slug);
                    $('input[name=menu_item_name]').val(title);
                    $('#addMenuPageModal').modal('hide');
                }
            </script>
        ");
    }

    class MenuAdmin extends Backend {
        
        public static function main() {

            // Get menu table
            $menu = new Table('menu');

            // Get pages table
            $pages = new Table('pages');

            // Create target array
            $menu_item_target_array = array( '' => '',
                                             '_blank' => '_blank',
                                             '_parent' => '_parent',
                                             '_top' => '_top');

            // Create order array
            $menu_item_order_array = range(0, 20);
            

            // Check for get actions
            // ---------------------------------------------
            if (Request::get('action')) {

                // Switch actions
                // -----------------------------------------
                switch (Request::get('action')) {

                    // Edit menu item
                    // -----------------------------------------    
                    case "edit":

                        // Select item
                        $item = $menu->select('[id="'.Request::get('item_id').'"]', null);
                        
                        $menu_item_name = $item['name'];
                        $menu_item_link = $item['link'];
                        $menu_item_target = $item['target'];
                        $menu_item_order = $item['order'];

                        $errors = array();

                        // Edit current menu item
                        if (Request::post('menu_add_item')) {
                            
                            if (trim(Request::post('menu_item_name')) == '') {

                                if (Request::post('menu_item_name')) $menu_item_name = Request::post('menu_item_name'); else $menu_item_name = $item['name'];
                                if (Request::post('menu_item_link')) $menu_item_link = Request::post('menu_item_link'); else $menu_item_link = $item['link'];
                                if (Request::post('menu_item_target')) $menu_item_target = Request::post('menu_item_target'); else $menu_item_target = $item['target'];
                                if (Request::post('menu_item_order')) $menu_item_order = Request::post('menu_item_order'); else $menu_item_order = $item['order'];

                                $errors['menu_item_name_empty'] = __('This field should not be empty', 'menu');
                            }

                            // Update menu item    
                            if (count($errors) == 0) {
                                $menu->update(Request::get('item_id'),array('name' => Request::post('menu_item_name'),
                                                                            'link' => Request::post('menu_item_link'),
                                                                            'target' => Request::post('menu_item_target'),
                                                                            'order' => Request::post('menu_item_order')));

                                Request::redirect('index.php?id=menu');
                            }

                        }

                        // Display view
                        View::factory('box/menu/views/backend/edit')
                                ->assign('menu_item_name', $menu_item_name)
                                ->assign('menu_item_link', $menu_item_link)
                                ->assign('menu_item_target', $menu_item_target)
                                ->assign('menu_item_order', $menu_item_order)
                                ->assign('menu_item_target_array', $menu_item_target_array)
                                ->assign('menu_item_order_array', $menu_item_order_array)
                                ->assign('errors', $errors)
                                ->assign('pages_list', $pages->select('[slug!="error404" and parent=""]'))
                                ->assign('components_list', MenuAdmin::getComponents())
                                ->display();

                              
                    break;
                    
                    // Add menu item
                    // -----------------------------------------    
                    case "add":

                        $menu_item_name = '';
                        $menu_item_link = '';
                        $menu_item_target = '';
                        $menu_item_order = '';
                        $errors = array();                    

                        // Add new menu item
                        if (Request::post('menu_add_item')) {
                            
                            if (trim(Request::post('menu_item_name')) == '') {

                                if (Request::post('menu_item_name')) $menu_item_name = Request::post('menu_item_name'); else $menu_item_name = '';
                                if (Request::post('menu_item_link')) $menu_item_link = Request::post('menu_item_link'); else $menu_item_link = '';
                                if (Request::post('menu_item_target')) $menu_item_target = Request::post('menu_item_target'); else $menu_item_target = '';
                                if (Request::post('menu_item_order')) $menu_item_order = Request::post('menu_item_order'); else $menu_item_order = '';

                                $errors['menu_item_name_empty'] = __('This field should not be empty', 'menu');
                            }

                            // Insert new menu item
                            if (count($errors) == 0) {
                                $menu->insert(array('name' => Request::post('menu_item_name'),
                                                    'link' => Request::post('menu_item_link'),
                                                    'target' => Request::post('menu_item_target'),
                                                    'order' => Request::post('menu_item_order')));

                                Request::redirect('index.php?id=menu');
                            }

                        }

                        // Display view
                        View::factory('box/menu/views/backend/add')
                                ->assign('menu_item_name', $menu_item_name)
                                ->assign('menu_item_link', $menu_item_link)
                                ->assign('menu_item_target', $menu_item_target)
                                ->assign('menu_item_order', $menu_item_order)
                                ->assign('menu_item_target_array', $menu_item_target_array)
                                ->assign('menu_item_order_array', $menu_item_order_array)
                                ->assign('errors', $errors)
                                ->assign('pages_list', $pages->select('[slug!="error404" and parent=""]'))
                                ->assign('components_list', MenuAdmin::getComponents())
                                ->display();

                    break;
                }
                
            } else {

                // Delete menu item
                if (Request::get('delete_item')) {
                    $menu->delete((int)Request::get('delete_item'));
                }
                
                // Select all items
                $items = $menu->select(null, 'all', null, array('id', 'name', 'link', 'target', 'order'), 'order', 'ASC');

                // Display view
                View::factory('box/menu/views/backend/index')
                        ->assign('items', $items)
                        ->display();

            }

        }
        

        /**
         * Get components
         */
        protected static function getComponents() {

            $components = array();
            
            if (count(Plugin::$components) > 0)  {
                foreach (Plugin::$components as $component) {
                    if ($component !== 'pages' && $component !== 'sitemap') $components[] = ucfirst($component);
                }
            }

            return $components;
        }

    }