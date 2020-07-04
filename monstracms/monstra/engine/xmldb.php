<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

    /**
     *  Monstra XMLDB API module. 
     * 
     *  Use SimpleXML and xPath to navigate through elements and attributes in an XML document.
     *  @link http://www.w3schools.com/xml/
     *  @link http://www.w3schools.com/xpath/
     *
     *  @package Monstra
     *  @subpackage Engine
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version $Id$
     *  @since 1.0.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  Monstra is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource
     */


    /**
     * XML class
     */
    class XML {


        /** 
         * Protected constructor since this is a static class.
         *
         * @access  protected
         */  
        protected function __construct() {
            // Nothing here
        }


        /**
         * Create safe xml data. Removes dangerous characters for string.
         *
         *  <code> 
         *      $xml_safe = XML::safe($xml_unsafe);
         *  </code>
         *
         * @param  string  $str  String
         * @param  boolean $flag Flag
         * @return string
         */     
        public static function safe($str, $flag = true) {

            // Redefine vars
            $str  = (string) $str;
            $flag = (bool)   $flag;

            // Remove invisible chars
            $non_displayables = array('/%0[0-8bcef]/', '/%1[0-9a-f]/', '/[\x00-\x08]/', '/\x0b/', '/\x0c/', '/[\x0e-\x1f]/');            
            do {
                $cleaned = $str;
                $str = preg_replace($non_displayables, '', $str);
            } while ($cleaned != $str);
            
            // htmlspecialchars
            if ($flag) $str = htmlspecialchars($str, ENT_QUOTES, 'utf-8'); 

            // Return safe string
            return $str; 
        } 


        /**
         * Get XML file
         *
         *  <code> 
         *      $xml_file = XML::loadFile('path/to/file.xml');
         *  </code>
         *
         * @param  string  $file  File name
         * @param  boolean $force Method
         * @return array
         */
        public static function loadFile($file, $force = false) {

            // Redefine vars
            $file  = (string) $file;
            $force = (bool)   $force;
            
            // For CMS API XML file force method
            if ($force) {
                $xml = file_get_contents($file);
                $data = simplexml_load_string($xml);
                return $data;
            } else {
                if (file_exists($file) && is_file($file)) {
                    $xml = file_get_contents($file);
                    $data = simplexml_load_string($xml);
                    return $data;
                } else {
                    return false;
                }
            }
        }

    }


    /**
     * DB Class
     */
    class DB {


        /**
         * XMLDB directory
         *
         * @var string
         */
        public static $db_dir = STORAGE;


        /** 
         * Protected constructor since this is a static class.
         *
         * @access  protected
         */  
        protected function __construct() {
            // Nothing here
        }

        
        /**
         * Configure the settings of XMLDB
         *
         * @param mixed $setting Setting name
         * @param mixed $value   Setting value
         */
        public static function configure($setting, $value){            
            if (property_exists("db", $setting)) DB::$$setting = $value;
        }

        
        /**
         * Create new database
         *
         * @param  string  $db_name Database name
         * @param  integer $mode    Mode
         * @return boolean
         */        
        public static function create($db_name, $chmod = 0775) {
            
            // Redefine vars
            $db_name = (string) $db_name;
            
            // Create
            if (is_dir(DB::$db_dir . '/' . $db_name)) return false;
            return mkdir(DB::$db_dir . '/' . $db_name, $chmod);
        }


        /**
         * Drop database 
         *
         * @param  string $db_name Database name
         * @return boolean
         */  
        public static function drop($db_name) {

            // Redefine vars
            $db_name = (string) $db_name;

            // Drop
            if (is_dir(DB::$db_dir . '/' . $db_name)){$ob=scandir(DB::$db_dir . '/' . $db_name); foreach ($ob as $o){if($o!='.'&&$o!='..'){if(filetype(DB::$db_dir . '/' . $db_name.'/'.$o)=='dir')DB::drop(DB::$db_dir . '/' . $db_name.'/'.$o); else unlink(DB::$db_dir . '/' . $db_name.'/'.$o);}}} 
            reset($ob); rmdir(DB::$db_dir . '/' . $db_name);  
        }

    }


    /**
     * Table class
     */
    class Table {


        /**
         * XMLDB Tables directory
         *
         * @var string
         */
        public static $tables_dir = XMLDB;


        /**
         * Table
         *
         * @var object
         */
        private $table;


        /**
         * Table name
         *
         * @var string
         */      
        private $name;


        /**
         * Configure the settings of XMLDB Tables
         *
         * @param mixed $setting Setting name
         * @param mixed $value   Setting value
         */
        public static function configure($setting, $value){            
            if (property_exists("table", $setting)) Table::$$setting = $value;
        }


        /**
         * Table construct
         *
         *  <code> 
         *      $users = new Table('table_name');
         *  </code>
         *
         * @param string $table_name Table name
         */
        function __construct($table_name) {

            // Redefine vars
            $table_name = (string) $table_name;

            $this->table = Table::get($table_name);
            $this->name  = $table_name;
        }


        /**
         * Create new table
         *
         * XMLDB Table structure:
         *
         *  <?xml version="1.0" encoding="UTF-8"?>
         *  <root>
         *      <options><autoincrement>0</autoincrement></options>
         *      <fields>
         *          <field1/>
         *          <field2/>   
         *      </fields>      
         *      <record>
         *          <field1>value</field1>
         *          <field2>value</field2>
         *      </record>
         *  </root> 
         *
         *  <code> 
         *      Table::create('table_name', array('field1', 'field2'));
         *  </code>
         *
         * @param  string $table_name Table name
         * @param  array  $fields     Fields
         * @return boolean
         */
        public static function create($table_name, $fields) {
            
            // Redefine vars
            $table_name = (string) $table_name;

            if ( ! file_exists(Table::$tables_dir . '/' . $table_name . '.table.xml') &&
                is_dir(dirname(Table::$tables_dir)) && 
                is_writable(dirname(Table::$tables_dir)) &&
                isset($fields) &&
                is_array($fields)) {

                // Create table fields 
                $_fields = '<fields>';
                foreach ($fields as $field) $_fields .= "<$field/>";
                $_fields .= '</fields>';

                // Create new table
                return file_put_contents(Table::$tables_dir . '/' . $table_name . '.table.xml','<?xml version="1.0" encoding="UTF-8"?><root><options><autoincrement>0</autoincrement></options>'.$_fields.'</root>', LOCK_EX); 

            } else {

                // Something wrong... return false
                return false;
            }
        }


        /**
         * Delete table
         *
         *  <code> 
         *      Table::drop('table_name');
         *  </code>
         *
         * @param  string $table_name Table name
         * @return boolean
         */
        public static function drop($table_name) {
            
            // Redefine vars
            $table_name = (string) $table_name;

            // Drop
            if ( ! is_dir(Table::$tables_dir . '/' . $table_name . '.table.xml')) {                
                return unlink(Table::$tables_dir . '/' . $table_name . '.table.xml');
            }
            
            return false;            
        }


        /**
         * Get table
         *
         *  <code> 
         *     $table = Table::get('table_name');
         *  </code>
         *
         * @param  array $table_name Table name
         * @return mixed
         */
        public static function get($table_name) { 
                 
            // Redefine vars
            $table_name = (string) $table_name;
 
            // Load table
            if (file_exists(Table::$tables_dir . '/' . $table_name.'.table.xml') && is_file(Table::$tables_dir . '/' . $table_name.'.table.xml')) {
                $data = array('xml_object'   => XML::loadFile(Table::$tables_dir . '/' . $table_name.'.table.xml'),
                              'xml_filename' => Table::$tables_dir . '/' . $table_name.'.table.xml');
                return $data;
            } else {
                return false;
            }
        }


        /**
         * Get information about table
         *
         *  <code>
         *      var_dump($users->info());
         *  </code>
         *
         * @return array
         */
        public function info() {            
            return array(            
                'table_name'        => basename($this->table['xml_filename'], '.table.xml'),
                'table_size'        => filesize($this->table['xml_filename']),
                'table_last_change' => filemtime($this->table['xml_filename']),
                'table_last_access' => fileatime($this->table['xml_filename']), 
                'table_fields'      => $this->fields(),
                'records_count'     => $this->count(), 
                'records_last_id'   => $this->lastId()
            );            
        }


        /**
         * Get table fields
         *
         *  <code> 
         *      var_dump($users->fields());
         *  </code>
         *
         * @return array
         */
        public function fields() {            
            
            // Select fields
            $fields_obj = Table::_selectOne($this->table, "fields");            
            
            // Create fields array            
            foreach ($fields_obj as $key => $field) {
                $fields[] = $key;
            }
            
            // Return array of fields
            return $fields;
        }


        /**
         * Add new field
         *
         *  <code> 
         *      $users->addField('test');
         *  </code>
         *
         * @param  string $name Field name
         * @return boolean
         */
        public function addField($name) {

            // Redefine vars
            $name = (string) $name;

            // Get table    
            $table = $this->table;
            
            // Select all fields 
            $fields = Table::_selectOne($this->table, "fields");

            // Select current field
            $field  = Table::_selectOne($this->table, "fields/{$name}");            

            // If field dosnt exists than create new field
            if ($field == null) {                
                
                // Create new field
                $fields->addChild($name, '');

                // Save table
                return Table::_save($table);

            } else {

                return false;

            }

        }


        /**
         * Delete field         
         *
         *  <code> 
         *      $users->deleteField('test');
         *  </code>
         *
         * @param  string $name Field name
         * @return boolean
         */
        public function deleteField($name) {
            
            // Redefine vars
            $name = (string) $name;
            
            // Get table    
            $table = $this->table;
            
            // Select field 
            $field = Table::_selectOne($this->table, "fields/{$name}");            

            // If field exist than delete it 
            if ($field != null) {                
                
                // Delete field
                unset($field[0]);                

                // Save table
                return Table::_save($table);

            } else {

                return false;

            }
        }


        /**
         * Add new record 
         *
         *  <code> 
         *      $users->insert(array('login'=>'admin', 'password'=>'pass'));
         *  </code>
         *
         * @param array $fields Record fields to insert
         * @param boolean
         */
        public function insert(array $fields = null) {            
            
            // Set save flag to true
            $save = true;          

            // Foreach fields check is current field alredy exists
            if (count($fields) !== 0) {                 
                foreach ($fields as $key => $value) {
                    if (Table::_selectOne($this->table, "fields/{$key}") == null) {
                        $save = false;
                        break;
                    }
                }
            }

            // Get table fields and create fields names array
            $_fields = Table::_selectOne($this->table, "fields");
            foreach ($_fields as $key => $value) {
                $field_names[(string)$key] = (string)$key;
            }

            // Save record
            if ($save) {
              
                // Find autoincrement option
                $inc = Table::_selectOne($this->table, "options/autoincrement");  
                
                // Increment        
                $inc_upd = $inc + 1;

                // Add record
                $node = $this->table['xml_object']->addChild(XML::safe($this->name));

                // Update autoincrement
                Table::_updateWhere($this->table, "options", array('autoincrement' => $inc_upd));

                // Add common record fields: id and uid
                $node->addChild('id', $inc_upd);
                $node->addChild('uid', Table::_generateUID());

                // If exists fields to insert then insert them
                if (count($fields) !== 0) {

                    $table_fields = array_diff_key($field_names, $fields);        
                    
                    // Defined fields
                    foreach ($table_fields as $table_field) {
                        $node->addChild($table_field, '');
                    }

                    // User fields
                    foreach ($fields as $key => $value) {                     
                        $node->addChild($key, XML::safe($value));
                    }
                }

                // Save table
                return Table::_save($this->table);

            } else {

                return false;

            }
        }


        /**
         * Select record(s) in table
         *
         *  <code> 
         *      $records = $users->select('[id=2]');
         *      $records = $users->select(null, 'all');
         *      $records = $users->select(null, 'all', null, array('login'));
         *      $records = $users->select(null, 2, 1);
         *  </code>
         *
         * @param string  $query     XPath query
         * @param integer $row_count Row count. To select all records write 'all'
         * @param integer $offset    Offset
         * @param array   $fields    Fields
         * @param string  $order_by  Order by
         * @param string  $order     Order type
         * @return array
         */
        public function select($query = null, $row_count = 'all', $offset = null, array $fields = null, $order_by = 'id', $order = 'ASC') {
        
            // Redefine vars
            $query    = ($query === null)  ? null : (string) $query;
            $offset   = ($offset === null) ? null : (int)    $offset;
            $order_by = (string) $order_by;            
            $order    = (string) $order;            

            // Execute query
            if ($query !== null) {
                $tmp = $this->table['xml_object']->xpath('//'.$this->name.$query);                    
            } else {
                $tmp = $this->table['xml_object']->xpath($this->name);
            }            

            // Init vars
            $data     = array();
            $records  = array();
            $_records = array();

            $one_record = false;
            
            // If row count is null then select only one record
            // eg: $users->select('[login="admin"]', null);
            if ($row_count == null) {                
                
                if (isset($tmp[0])) {                    
                    $_records   = $tmp[0];
                    $one_record = true;
                }     
                           
            } else {

                // If row count is 'all' then select all records
                // eg: 
                //     $users->select('[status="active"]', 'all'); 
                // or
                //     $users->select('[status="active"]'); 
                if ($row_count == 'all') {
                    
                    foreach ($tmp as $record) {                        
                        $data[] = $record;
                    } 
                      
                    $_records = $data;    
                
                } else {

                    // Else select records like
                    // eg: $users->select(null, 2, 1);

                    foreach($tmp as $record) {
                        $data[] = $record;
                    }

                    // If offset is null slice array from end else from begin
                    if ($offset === null) {
                        $_records = array_slice($data, -$row_count, $row_count);
                    } else {
                        $_records = array_slice($data, $offset, $row_count);
                    }
                 
                }
            }

            // If array of fields is exits then get records with this fields only
            if (count($fields) > 0) { 
                
                if (count($_records) > 0) {
                    
                    $count = 0;
                    foreach ($_records as $key => $record) {
                
                        foreach ($fields as $field) {
                            $record_array[$count][$field] = (string)$record->$field;
                        }
                    
                        //$record_array[$count]['id'] = (int)$record['id'];
                        $record_array[$count]['id'] = (int)$record->id;

                        if ($order_by == 'id') {                                                            
                            $record_array[$count]['sort'] = (int)$record->$order_by;
                        } else {                            
                            $record_array[$count]['sort'] = (string)$record->$order_by;
                        }

                        $count++;

                    }
                    $records = Table::subvalSort($record_array, 'sort', $order);                    
                }

            } else {                 
                
                // Convert from XML object to array   
                
                if ( ! $one_record) {
                    $count = 0;  
                    foreach ($_records as $xml_objects) {
                        $vars = get_object_vars($xml_objects);                                                                            
                        foreach ($vars as $key => $value) {                                                                                                           
                            $records[$count][$key] = (string)$value;                                                                                                                 
                        }
                        $count++;                            
                    }
                } else {
                    $vars = get_object_vars($_records[0]);                                      
                    foreach ($vars as $key => $value) {   
                        $records[$key] = (string)$value;              
                    }
                }                            
                
            }
            
            // Return records
            return $records;
     
        }


        /**
         * Delete current record in table
         *
         *  <code> 
         *      $users->delete(2);
         *  </code>
         *
         * @param  integer $id Record ID
         * @return boolean
         */
        public function delete($id) {
            
            // Redefine vars
            $id = (int) $id;

            // Find record to delete
            $xml_arr = Table::_selectOne($this->table, "//".$this->name."[id='".$id."']");

            // If its exists then delete it
            if (count($xml_arr) !== 0) {

                // Delete
                unset($xml_arr[0]);

            }

            // Save table
            return Table::_save($this->table);
        }


        /**
         * Delete with xPath query record in xml file
         *
         *  <code> 
         *      $users->deleteWhere('[id=2]');
         *  </code>
         *
         * @param  string $query xPath query
         * @return boolean
         */
        public function deleteWhere($query) {
            
            // Redefine vars
            $query = (string) $query;

            // Find record to delete
            $xml_arr = Table::_selectOne($this->table, '//'.$this->name.$query);
            
            // If its exists then delete it
            if (count($xml_arr) !== 0) {

                // Delete
                unset($xml_arr[0]);

            }

            // Save table
            return Table::_save($this->table);
        }


        /**
         * Update record with xPath query in XML file
         *
         *  <code> 
         *      $users->updateWhere('[id=2]', array('login'=>'Admin', 'password'=>'new pass'));
         *  </code>
         *
         * @param  string $query  XPath query
         * @param  array  $fields Record fields to udpate
         * @return boolean
         */
        public function updateWhere($query, array $fields = null) {
            
            // Redefine vars
            $query = (string) $query;

            // Set save flag to true
            $save = true;

            // Foreach fields check is current field alredy exists
            if (count($fields) !== 0) {
                foreach ($fields as $key => $value) {
                    if (Table::_selectOne($this->table, "fields/{$key}") == null) {
                        $save = false;
                        break;
                    }
                }
            }

            // Get table fields and create fields names array
            $_fields = Table::_selectOne($this->table, "fields");
            foreach ($_fields as $key => $value) {
                $field_names[(string)$key] = (string)$key;
            }
            
            // Save record
            if ($save) {
           
                // Find record
                $xml_arr = Table::_selectOne($this->table, '//'.$this->name.$query);

                // If its exists then delete it
                if (count($fields) !== 0) {
                    foreach ($fields as $key => $value) {
                        // Else: Strict Mode Error
                        // Creating default object from empty value
                        @$xml_arr->$key = XML::safe($value, false);
                    }
                }

                // Save table
                return Table::_save($this->table);

            } else {

                return false;

            }
        }


        /**
         * Update current record in table
         *
         *  <code> 
         *      $users->update(1, array('login'=>'Admin','password'=>'new pass'));
         *  </code>
         *
         * @param  integer $id     Record ID
         * @param  array   $fields Record fields to udpate
         * @return boolean
         */
        public function update($id, array $fields = null) {
            
            // Redefine vars
            $id = (int) $id;

            // Set save flag to true
            $save = true;

            // Foreach fields check is current field alredy exists
            if (count($fields) !== 0) {
                foreach ($fields as $key => $value) {
                    if (Table::_selectOne($this->table, "fields/{$key}") == null) {
                        $save = false;
                        break;
                    }
                }
            }

            // Get table fields and create fields names array
            $_fields = Table::_selectOne($this->table, "fields");
            foreach ($_fields as $key => $value) {
                $field_names[(string)$key] = (string)$key;
            }

            // Save record
            if ($save) {

                // Find record to delete
                $xml_arr = Table::_selectOne($this->table, "//".$this->name."[id='".(int)$id."']");
                        
                // If its exists then update it
                if (count($fields) !== 0) {
                    foreach ($fields as $key => $value) {
                        
                        // Delete current
                        unset($xml_arr->$key);

                        // And add new one 
                        $xml_arr->addChild($key, XML::safe($value, false));   
                                            
                    }
                }

                // Save table
                return Table::_save($this->table);

            } else {

                return false;

            }
        }


        /**
         * Get last record id
         *
         *  <code>
         *      echo $users->lastId();
         *  </code>
         *
         * @return integer
         */
        public function lastId() {
            $data = $this->table['xml_object']->xpath("//root/node()[last()]");            
            return (int)$data[0]->id;
        }


        /**
         * Get count of records
         *
         *  <code>
         *      echo $users->count();
         *  </code>
         *
         * @return integer
         */
        public function count() {
            return count($this->table['xml_object'])-2;            
        }     



        /**
         * Subval sort
         *
         * @param  array  $a      Array
         * @param  string $subkey Key
         * @param  string $order  Order type DESC or ASC
         * @return array
         */
        protected static function subvalSort($a, $subkey, $order = null) {            
            if (count($a) != 0 || (!empty($a))) {
                foreach ($a as $k=>$v) $b[$k] = function_exists('mb_strtolower') ? mb_strtolower($v[$subkey]) : strtolower($v[$subkey]);                
                if ($order==null || $order== 'ASC') asort($b); else if ($order == 'DESC') arsort($b);                                    
                foreach ($b as $key=>$val) $c[] = $a[$key];
                return $c;
            }
        }


        /**
         * _selectOne
         */        
        protected static function _selectOne($table, $query) {
            $tmp = $table['xml_object']->xpath($query);                        
            return isset($tmp[0])? $tmp[0]: null;
        }


        /**
         * _updateWhere
         */ 
        protected static function _updateWhere($table, $query, $fields = array()) {          
           
            // Find record to delete
            $xml_arr = Table::_selectOne($table, $query);

            // If its exists then delete it
            if (count($fields) !== 0) {
                foreach ($fields as $key => $value) {
                    $xml_arr->$key = XML::safe($value, false);
                }
            }

            // Save table
            Table::_save($table);
        }


        /**
         * _generateUID
         */        
        protected static function _generateUID() {
            return substr(md5(uniqid(rand(), true)), 0, 10);
        }


        /**
         * Format XML and save
         *
         * @param array $table Array of database name and XML object
         */
        protected static function _save($table) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;

            // Save new xml data to xml file only if loadXML successful.        
            // Preventing the destruction of the database by unsafe data.
            // note: If loadXML !successful then _save() add&save empty record.
            //       This record cant be removed by delete[Where]() Problem solved by hand removing...
            //       Possible solution: modify delete[Where]() or prevent add&saving of such records.
            // the result now: database cant be destroyed :)
            if ($dom->loadXML($table['xml_object']->asXML())) {              
                $dom->save($table['xml_filename']);
                return true;               
            } else {        
                return false;    
                // report about errors...
            }       
        }

    }