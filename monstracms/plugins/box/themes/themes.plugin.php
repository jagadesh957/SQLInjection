<?php

    /**
     *  Themes plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version 1.0.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Themes', 'themes'),
                    __('Themes manager', 'themes'),  
                    '1.0.0',
                    'Awilum',                 
                    'http://monstra.org/',
                    null,
                    'box');


    if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin'))) {
        
        // Include Admin
        Plugin::admin('themes', 'box');

    }


    class Themes {
        

        /**
         * Get Themes
         */
        public static function getThemes() {

            $themes_folders = array(); 

            // Get all themes folders
            $_themes_folders = Dir::scan(THEMES);     
                   
            // Create an array of valid themes folders
            foreach($_themes_folders as $folder) if (File::exists(THEMES . DS . $folder . DS . 'index.template.php')) $__themes_folders[] = $folder;
            foreach($__themes_folders as $theme) $themes[$theme] = $theme;

            return $themes;
        }

        /**
         * Get Templates
         *
         * @param string $theme Theme name
         * @return array
         */
        public static function getTemplates($theme = null) {

            $theme = ($theme === null) ? null : (string) $theme;

            if ($theme == null) $theme = Option::get('theme_name');

            $templates = array();

            // Get all templates in current theme folder
            $templates = File::scan(THEMES . DS . $theme, '.template.php');

            return $templates;
        }


        /**
         * Get Chunks
         *
         * @param string $theme Theme name
         * @return array
         */
        public static function getChunks($theme = null) {

            $theme = ($theme === null) ? null : (string) $theme;

            if ($theme == null) $theme = Option::get('theme_name');

            $chunks = array();

            // Get all templates in current theme folder
            $chunks = File::scan(THEMES . DS . $theme, '.chunk.php');

            return $chunks;
        }

        /**
         * Get Styles
         *
         * @param string $theme Theme name
         * @return array
         */
        public static function getStyles($theme = null) {

            $theme = ($theme === null) ? null : (string) $theme;

            if ($theme == null) $theme = Option::get('theme_name');

            $styles = array();

            // Get all templates in current theme folder
            $styles = File::scan(THEMES . DS . $theme . DS . 'css', '.css');

            return $styles;
        }

    }


    /**
     * Chunk frontend class
     */
    class Chunk {

        /** 
         * Get chunk
         *
         * @param string $name Chunk name
         * @param string $theme Theme name
         */
        public static function get($name, $theme = null) {
            
            $name  = (string) $name;
            $current_theme = ($theme === null) ? null : (string) $theme;

            if ($current_theme == null) $current_theme = Option::get('theme_name');

            $chunk_path  = THEMES . DS . $current_theme . DS;

            // Is chunk exist ? 
            if (file_exists($chunk_path . $name . '.chunk.php')) {

                // Is chunk minified
                if ( ! file_exists(MINIFY . DS . 'theme.' . $current_theme . '.minify.' . $name . '.chunk.php') or 
                    filemtime(THEMES . DS . $current_theme . DS . $name .'.chunk.php') > filemtime(MINIFY . DS . 'theme.' . $current_theme . '.minify.' . $name . '.chunk.php')) {
                        $buffer = file_get_contents(THEMES. DS . $current_theme . DS . $name .'.chunk.php');
                        $buffer = Minify::html($buffer);
                        file_put_contents(MINIFY . DS . 'theme.' . $current_theme . '.minify.' . $name . '.chunk.php', $buffer);
                } 

                // Include chunk
                include MINIFY . DS . 'theme.' . $current_theme . '.minify.' . $name . '.chunk.php';
            }

        }

    }