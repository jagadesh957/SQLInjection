<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

    /**
     *	Directory Helper
     *
     *	@package Monstra
     *	@subpackage Helpers
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 1.0.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  Monstra is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


	/**
	 * Dir class
	 */
	class Dir {

        
        /** 
         * Protected constructor since this is a static class.
         *
         * @access  protected
         */  
        protected function __construct() {
            // Nothing here
        }


	    /**
	     * Creates a directory
         *
         *  <code>
         *      Dir::create('folder1');
         *  </code>
         *
	     * @param  string  $dir   Name of directory to create
         * @param  integer $chmod Chmod
	     * @return boolean
	     */
	    public static function create($dir, $chmod = 0775) {	
	    	
	    	// Redefine vars
			$dir = (string) $dir;

			// Create new dir if $dir !exists
			return ( ! Dir::exists($dir)) ? @mkdir($dir, $chmod, true) : true;
	    }


		/**
		 * Checks if this directory exists.
         *
         *  <code>
         *      if (Dir::exists('folder1')) {
         *          // Do something...       
         *      }
         *  </code>
         *
		 * @param	string  $dir Full path of the directory to check.
		 * @return	boolean				
		 */
		public static function exists($dir) {

			// Redefine vars
			$dir = (string) $dir;

			// Directory exists
			if (file_exists($dir) && is_dir($dir)) return true;

			// Doesn't exist
			return false;
		}

		
	    /**
	     * Check dir permission
         *
         *  <code>
         *      echo Dir::checkPerm('folder1');
         *  </code>
         *
	     * @param  string $dir Directory to check
	     * @return string
	     */
	    public static function checkPerm($dir) {
			
			// Redefine vars
			$dir = (string) $dir;
			
			// Clear stat cache
	        clearstatcache();

	        // Return perm
	        return substr(sprintf('%o', fileperms($dir)), -4);
	    }
	    

	    /**
	     * Delete directory
         *
         *  <code>
         *      Dir::delete('folder1');
         *  </code>
         *
	     * @param string $dir Name of directory to delete
	     */
	    public static function delete($dir) { 
	    	
	    	// Redefine vars
			$dir = (string) $dir;

			// Delete dir
	        if (is_dir($dir)){$ob=scandir($dir);foreach ($ob as $o){if($o!='.'&&$o!='..'){if(filetype($dir.'/'.$o)=='dir')Dir::delete($dir.'/'.$o); else unlink($dir.'/'.$o);}}} 
	        reset($ob); rmdir($dir);          
	    } 


	    /**
	     * Get list of directories
         *
         *  <code>
         *      $dirs = Dir::scan('folders');
         *  </code>
         *
	     * @param string $dir Directory
	     */
	    public static function scan($dir){        
			
			// Redefine vars
			$dir = (string) $dir;
			
			// Scan dir
			if (is_dir($dir)&&$dh=opendir($dir)){$f=array();while ($fn=readdir($dh)){if($fn!='.'&&$fn!='..'&&is_dir($dir.DS.$fn))$f[]=$fn;}return$f;}
	    }


		/**
		 * Check if a directory is writable.
         *
         *  <code>
         *      if (Dir::writable('folder1')) {
         *          // Do something...       
         *      }
         *  </code>
         *
		 * @param	string   $path	The path to check.
         * @return  booleans
		 */
		public static function writable($path) {

			// Redefine vars
			$path = (string) $path;

			// Create temporary file
			$file = tempnam($path, 'writable');

			// File has been created
			if($file !== false) {

				// Remove temporary file
				File::delete($file);

				//  Writable
				return true;
			}

			// Else not writable
			return false;
		}

	    
	}
