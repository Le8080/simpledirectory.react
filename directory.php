<?php

class Directories {
    var $categories;
    var $type;
    /**
     * Directory CLass constructor
     *
     * @param string $type 
     * @param object $DBconnections 
     */
    public function __construct(){
        $this->categories = $this->set_type();
    }

    public function get_directories($type){
        global $DB;
        $records = $DB->get_records('SELECT * from ? ',array($type));
        return $records;
    }
    public function get_sql_directories($sql,$param){
        global $DB;
        $records = $DB->get_records($sql,$param);
        return $records;
    }
    public function get_directory($type,$param){
        global $DB;
        $records = $DB->get_record($type,$param);
        return $records;
    }
    public function set_type(){
        $categories = new stdClass();
        $categories->hotel = 'Hotel';
        $categories->restaurant = 'Restaurant';
        $categories->school = 'School';
        return $categories;
    }
    public function get_type(){
        return $this->categories;
    }


    public function get_directory_details(){

    }
}


function di($value){
    print_r('<pre>');
    print_r($value);
    print_r('</pre>');
}


?>
