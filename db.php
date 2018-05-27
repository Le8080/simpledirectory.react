<?php

class DBobject{
    private static $_instance;
    private $_connect;
    private $_host;
    private $_password;
    private $_username;
    private $_dbname;
    
    /**
     * Get Database instance
     *
     * @return instance
     */
    public static function DBInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    private function __clone() {
        //Let's prevent duplication of connection
    }
    
    /**
     * Constructor
     * This is where the connection of the database takes place
     * and declaration of some of the variables
     * 
     * TODO : fetching database credentials
     */
    private function __construct(){
        $this->_host = 'localhost';
        $this->_password = 'password';
        $this->_username = 'root';
        $this->_dbname = 'searchandsort';
        $this->_password = '';

       
        $connect = $this->__connect();
        if(!$connect){
            throw new Exception('Unable to connect to Mysql'.PHP_EOL.' Debugging no.'.mysqli_connect_errno().PHP_EOL
            .' Debugging Error Message '.mysqli_connect_error);
        }
        //catch error connection;
        try{
            $this->__connect();
        }catch(Exception $error){
            $connect = trigger_error('Caught Exception : '.$error->message);
        }finally{
            return $connect;
        }
        return $connect;
    }

    /**
     * Close the database connection
     * @return true
     */
    public function __destruct() {
        $this->_connect->close();
        return true;
    }

    /**
     * function to call on connecting to the datase
     *
     * @return void
     */
    private function __connect(){
        return $this->_connect = new mysqli($this->_host,$this->_username,$this->_password,$this->_dbname);
    }

    /**
     * gets the connection
     *
     * @return void
     */
    public function getDBConnect(){
        return $this->__connect();
    }
    
    /**
     * Get single record of a given table and parameters
     *
     * @param string $table
     * @param array $params
     * @return object
     */
    public function get_record($table = '', $params = array()){
        if($table AND !empty($params)){

            $whereparam = '';
            //setup query params
            foreach($params as $p=>$param){
                if(!empty($whereparam))
                    $whereparam .=" AND $p = '$param' ";
                else $whereparam .=" $p = '$param' ";
            }
            //prepare sql
            $sql = "SELECT * FROM $table where $whereparam LIMIT 1";
            //get data
            $record =  $this->_connect->query($sql);
            if(!empty($record)){
                //fetch data as object
                return  (mysqli_fetch_object($record));
            }
        }
        return false;
    }
    
    /**
     * Get records of a given table and parameters
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function get_records($sql = '', $params = array()){
        if($sql){
            if(!empty($params)){
                preg_match_all('(:|\?)',$sql,$matches);
                $totalmatches = count($matches[0]);
                $totalcondition = count($params);
                if(in_array('?',$matches[0])){
                    //check if the query condition consist the same count on provided parameters for our Where Clause
                    if($totalcondition < $totalmatches){
                        return trigger_error('Caught Exception : Expecting '.$totalmatches.' but recieves '.$totalcondition);
                    }
                    foreach($params as $param){
                        $sql = preg_replace('/\?/',$param,$sql,1);
                    }
                }else if(in_array(':',$matches[0])){
                    foreach($params as $p=>$param){
                        $patterns[$p] = "/:$p/";
                        $replace[$p] = $param;
                    }   
                    $sql = preg_replace($patterns,$replace,$sql);
                }
            }
            //get data
            $record =  $this->_connect->query($sql);
            if(!empty($record)){
            //fetch data as object
                $records = array();
                while($row  =mysqli_fetch_object($record)){
                    $records[$row->id] = $row;
                }
                return $records;
                return mysqli_fetch_object($record);
            }else{
                return false;
            }
        }

        return false;        
    }
}