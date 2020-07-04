<?php

    /**
     *	Sitemap plugin
     *
     *	@package Monstra
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2012 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Sitemap', 'sitemap'),
                    __('Sitemap plugin', 'sitemap'),  
                    '1.0.0',
                    'Awilum',                 
                    'http://monstra.org/',   
                    'sitemap',
                    'box');


    class Sitemap extends Frontend {


       /**
        * Get title
        *
        * @return string
        */
       public static function title() {
            return __('Sitemap', 'sitemap'); 
       }


       /**
        * Get sitemap content
        */
       public static function content() {
            
            // Get pages table
            $pages = new Table('pages');

            $pages_list = $pages->select('[slug!="error404" and status="published"]');
            
            $pages_array = array();

            $count = 0;
            
            foreach ($pages_list as $page) {

                $pages_array[$count]['title']   = $page['title'];
                $pages_array[$count]['parent']  = $page['parent'];
                $pages_array[$count]['date']    = $page['date'];
                $pages_array[$count]['author']  = $page['author'];
                $pages_array[$count]['slug']    = $page['slug'];

                if (isset($page['parent'])) {
                    $c_p = $page['parent'];
                } else {
                    $c_p = '';
                }

                if ($c_p != '') {
                    $_page = $pages->select('[slug="'.$page['parent'].'"]', null);

                    if (isset($_page['title'])) {
                        $_title = $_page['title'];
                    } else {
                        $_title = '';
                    }
                    $pages_array[$count]['sort'] = $_title . ' ' . $page['title'];                
                } else {
                    $pages_array[$count]['sort'] = $page['title'];     
                }
                $_title = '';                     
                $count++;                    
            }

            // Sort pages
            $_pages_list = Arr::subvalSort($pages_array, 'sort');

            // Display view
            return View::factory('box/sitemap/views/frontend/index')
                          ->assign('pages_list', $_pages_list)
                          ->assign('components', Sitemap::getComponents())
                          ->render();
        }
        

        /**
         * Create sitemap
         */
        public static function create() {

            // Get pages table
            $pages = new Table('pages');

            // Get pages list
            $pages_list = $pages->select('[slug!="error404" and status="published"]');
            
            // Create sitemap content    
            $map = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $map .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($pages_list as $page) {
                if ($page['parent'] != '') { $parent = $page['parent'].'/'; } else { $parent = ''; }
                $map .= "\t".'<url>'."\n\t\t".'<loc>'.Option::get('siteurl').$parent.$page['slug'].'</loc>'."\n\t\t".'<lastmod>'.date("Y-m-d", (int)$page['date']).'</lastmod>'."\n\t\t".'<changefreq>weekly</changefreq>'."\n\t\t".'<priority>1.0</priority>'."\n\t".'</url>'."\n";
            }

            // Get list of components
            $components = Sitemap::getComponents();

            // Add components to sitemap
            if (count($components) > 0)  {                
                foreach ($components as $component) {                    
                    $map .= "\t".'<url>'."\n\t\t".'<loc>'.Option::get('siteurl').Text::lowercase($component).'</loc>'."\n\t\t".'<lastmod>'.date("Y-m-d", time()).'</lastmod>'."\n\t\t".'<changefreq>weekly</changefreq>'."\n\t\t".'<priority>1.0</priority>'."\n\t".'</url>'."\n";
                }                
            }
    
            // Close sitemap
            $map .= '</urlset>';

            // Save sitemap
            return File::setContent('../sitemap.xml', $map);
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