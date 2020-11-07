<?php
  class TableSqlSrv extends SqlSrv
  {
    /*
		  private $host = '10.1.18.17';
      private $user = 'imap';
      private $pass = 'secsvr';
      private $db = 'KSL_IMAP_01';
      */

      private $host = '';
      private $user = '';
      private $pass = '';
      private $db = '';

      private $conn = null;
      
      private $encode_data = 'th';
      private $table_name = '';

      private static $_instance;
      
    function __set($k,$v){
      $k = strtolower(trim($k));
      $this->{$k}=$v;
    }
          
    function __construct(){
      
      global $DB_SOFTPRO,  $AR_CONFIG_SOFTPRO;
      $this->db = $DB_SOFTPRO;
      $this->host = $AR_CONFIG_SOFTPRO['host_name'];
      $this->user = $AR_CONFIG_SOFTPRO['user_name'];
      $this->pass = $AR_CONFIG_SOFTPRO['pass_name'];
      $this->conn = New SqlSrv($this->host,$this->user,$this->pass,$this->db);
      return $this;
    }

   // Get an instance of the Database.
    // @return Database: 
    public static function getInstance() {
      if (!self::$_instance) {
          self::$_instance =  new self();
      }
      return self::$_instance;
    }

      public function getConnection(){
        return $this->conn;
      }

    
    function __destruct(){
       $this->field = "*";
       $this->where = "1=1";
       $this->group = "";
       $this->order = "";
       //$this->sql = '';
    }

 
    
    function db_connect($db="",$server="softpro"){ // $server="imap"

      global  $AR_CONFIG_SOFTPRO;
      
      if(!$this->is_blank($db))  $this->db = $db;
      
      switch(strtolower($server)){
        case "softpro":
           $this->host = $AR_CONFIG_SOFTPRO['host_name'];
           $this->user = $AR_CONFIG_SOFTPRO['user_name'];
           $this->pass = $AR_CONFIG_SOFTPRO['pass_name'];
        break;
      }
      
      $this->conn = New SqlSrv($this->host,$this->user,$this->pass,$this->db);
      //return $this->conn;
    }
    
   public  function run_query($sql=''){
      if($sql)
        $this->sql = $sql;
      $data = $this->conn->select($this->sql);
      $data = $this->MSSQLEncodeTH2D($data);
      return $data;
    }

   public function run_query2($sql=''){
      if($sql)
        $this->sql = $sql;
      $data = $this->conn->select($this->sql);
      $data = $this->MSSQLEncodeTH2D2($data);
      return $data;
    }
    
    function run_exec($sql=''){
      if($sql)
        $this->sql = $sql;
      $data = $this->conn->select($this->sql);
      return $data;
    }

    function _insert($table_name,$data,$show_sql=0){
      return $this->conn->insert($table_name,$data,$show_sql);
    }
    
    
    private $field = "*";
    private $where = "1=1";
    private $group = "";
    private $order = "";
    private $sql = '';
    
    function _where($cond,$op=" and "){
      $this->where .= " {$op} ($cond) ";
      return $this;
    }
    function _field($fd){
      $this->field = $fd;
      return $this;
    }
    function _groupby($gb){
      $this->group = $gb;
      return $this;
    }
    /**
    * generate select data
    * @key := field,where,group,order
    * @val := array of key
    * 
    * @return
    */
    function assign_rows($key,$val=array(),$type=' and '){
      $ret = array();
      if($key === "where"){
        
        if($val)
          foreach($val as $k=>$v){
            $ret[] = " {$k} = '{$v}'";
          }
        $this->{$key} = implode($type,$ret);
      
      }else  
        $this->{$key} = implode(",",$val);
      
      
      return $this;
    }
    
    function get_rows(){
      if(!$this->is_blank($this->group))
         $this->group = "group by {$this->group}";
      if(!$this->is_blank($this->order))
         $this->order = "order by {$this->order}";   
         
      $this->sql = 'SELECT top 1 '.$this->field.' FROM '.$this->table_name." WHERE {$this->where} {$this->group} {$this->order}";
      $data = $this->run_query($this->sql);
      $this->__destruct();
      return $data[0];
    }
    
    function get_rows_all(){
      if(!$this->is_blank($this->group))
         $this->group = "group by {$this->group}";
      if(!$this->is_blank($this->order))
         $this->order = "group by {$this->order}";   
         $this->sql = 'SELECT  '.$this->field.' FROM '.$this->table_name." WHERE {$this->where} {$this->group} {$this->order}";
     $data = $this->run_query($this->sql);
     $this->__destruct();
      return $data;

    }
    
    function get_count(){
      $this->sql = "SELECT count(*) as x FROM {$this->table_name} where {$this->where} ";
      $data = $this->run_query($this->sql);
      return (int)$data[0]['x'];
    }
    
    function get_sql(){
      return $this->sql;
    }
    
    
    
  }
