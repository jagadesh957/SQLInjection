<?php  if ( ! defined('MONSTRA_ACCESS')) exit('No direct script access allowed');

    // Add plugin navigation link
    Navigation::add(__('Plugins', 'plugins'), 'extends', 'plugins', 1);

    class PluginsAdmin extends Backend {

        /**
         * Plugins admin
         */
        public static function main() {

            // Get siteurl
            $site_url = Option::get('siteurl');
            
            // Get installed plugin from $plugins array
            $installed_plugins = Plugin::$plugins;

            // Get installed users plugins
            $_users_plugins = array();
            foreach(Plugin::$plugins as $plugin) {
                if($plugin['privilege'] !== 'box') $_users_plugins[] = $plugin['id'];
            }

            // Get plugins table
            $plugins = new Table('plugins');          

            // Delete plugin
            // -------------------------------------   
            if (Request::get('delete_plugin')) {  
                      
                // Nobody cant remove box plugins
                if ($installed_plugins[Text::lowercase(str_replace("Plugin", "", Request::get('delete_plugin')))]['privilege'] !== 'box') {
                    
                    // Run plugin uninstaller file
                    $plugin_name = Request::get('delete_plugin');
                    if(File::exists('../plugins/'.$plugin_name.'/'.'install/'.$plugin_name.'.uninstall.php')) {
                        include '../plugins/'.$plugin_name.'/'.'install/'.$plugin_name.'.uninstall.php';
                    }
                    
                    // Clean i18n cache
                    Cache::clean('i18n');
                    
                    // Delete plugin form plugins table                
                    $plugins->deleteWhere('[name="'.Request::get('delete_plugin').'"]');
                   
                    Request::redirect('index.php?id=plugins');                               
                } 
            }


            // Install new plugin
            // -------------------------------------   
            if (Request::get('install')) {                          

                // Load plugin install xml file
                $plugin_xml = XML::loadFile('../plugins/'.basename(Text::lowercase(Request::get('install')), '.manifest.xml').'/'.'install/'.Request::get('install'));           

                // Add plugin to plugins table
                $plugins->insert(array('name'     => basename(Request::get('install'), '.manifest.xml'),                                       
                                       'location' => (string)$plugin_xml->plugin_location,
                                       'status'   => (string)$plugin_xml->plugin_status,
                                       'priority' => (int)$plugin_xml->plugin_priority));
          
                // Clean i18n cache
                Cache::clean('i18n');                
          
                // Run plugin installer file
                $plugin_name = str_replace(array("Plugin", ".manifest.xml"), "", Request::get('install'));
                if(File::exists('../plugins/'.basename(Text::lowercase(Request::get('install')), '.manifest.xml').'/'.'install/'.$plugin_name.'.install.php')) {
                    include '../plugins/'.basename(Text::lowercase(Request::get('install')), '.manifest.xml').'/'.'install/'.$plugin_name.'.install.php';
                }
    
                Request::redirect('index.php?id=plugins');                                  
            }


            // Delete plugin from server
            // -------------------------------------
            if (Request::get('delete_plugin_from_server')) {
                Dir::delete('../plugins/'.basename(Request::get('delete_plugin_from_server'), '.manifest.xml'));
                Request::redirect('index.php?id=plugins');
            }
            

            // Installed plugins
            $plugins_installed = array();

            // New plugins
            $plugins_new = array();

            // Plugins to install
            $plugins_to_intall = array(); 

            // Scan plugins directory for .manifest.xml
            $plugins_new = File::scan('../plugins', '.manifest.xml');

            // Get installed plugins from plugins table
            $plugins_installed = $plugins->select(null, 'all', null, array('location', 'priority'), 'priority', 'ASC');

            // Update $plugins_installed array. extract plugins names
            foreach ($plugins_installed as $plg) {
                $_plg[] = basename($plg['location'], 'plugin.php').'manifest.xml';
            }

            // Diff
            $plugins_to_install = array_diff($plugins_new, $_plg);

            // Create array of plugins to install
            $count = 0;
            foreach ($plugins_to_install as $plugin) {
                $plg_path = '../plugins/'.Text::lowercase(basename($plugin, '.manifest.xml')).'/install/'.$plugin;
                if (file_exists($plg_path)) {
                    $plugins_to_intall[$count]['path']   = $plg_path;
                    $plugins_to_intall[$count]['plugin'] = $plugin;
                    $count++;
                } 
            }

            // Draw template
            View::factory('box/plugins/views/backend/index')
                    ->assign('installed_plugins', $installed_plugins)
                    ->assign('plugins_to_intall', $plugins_to_intall)
                    ->assign('_users_plugins', $_users_plugins)
                    ->display();
        }
    }