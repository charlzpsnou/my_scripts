<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 20.01.16
 * Time: 16:24
 */
CLASS model{
    const MYSQLI_HOST="localhost";
    const MYSQLI_USER="root";
    const  MYSQLI_PASS="svitstore";
    const  MYSQLI_DB="iteneraryDB";

    public $mysqli;
    private $mainTable="itenerary";
    private $notification="";
    static private $_instance=0;


    private function __construct($mysqli_host=self::MYSQLI_HOST, $mysqli_user=self::MYSQLI_USER, $mysqli_pass=self::MYSQLI_PASS, $mysqli_db=self::MYSQLI_DB){

            $this->mysqli=new mysqli($mysqli_host, $mysqli_user, $mysqli_pass, $mysqli_db);
        if ($this->mysqli->connect_errno){throw new Exception("Не удалось подключиться к базе данных '{$this->mysqli->connect_error}'");}
//            if($this->mysqli->connect_errno){echo "Something wrong:".$this->mysqli->connect_error."<br>"; }
//            else {echo "Connection to DATABASE was succesfull <br>";}
    }

    static public function getDatabaseInstance(){
        if(self::$_instance===0){

            self::$_instance=new self;
        }
        return self::$_instance;
    }

    public function getRowsFromTable($table){
        $query="SELECT * FROM $table";
        $this->mysqli->real_query($query);
        if($result=$this->mysqli->store_result()){
            $i=0;
            while($result->data_seek($i)){
                $i++;
                $itenerary_list[]=$result->fetch_assoc();
            }
        return $itenerary_list;
        }
        else {
            echo "something wrong with statement: ".$this->mysqli->error;
        }
    }
    public function getRowsFromTableWithParams($itenerary_type, $itenerary_date ){
        $table=$this->mainTable;

        $query="SELECT i_list FROM $table WHERE i_type=? AND i_date=?";

        $stmt=$this->mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('ss', $itenerary_type, $itenerary_date);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($json_i_list);
        if($stmt->fetch()!==null){
            $stmt->close();
            $itenerary_list=json_decode($json_i_list);
            return $itenerary_list;
        }
        else {
            $stmt->close();
            $notification[][]=$this->notification="К сожалению, по заданным параметрам не нашлось совпадений в базе данных";
            return $notification;
        }
    }

    public function getPatternsByType($itenerary_type){
        $table=$this->mainTable;

        $query="SELECT i_list FROM $table WHERE i_type=?";

        $stmt=$this->mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('s', $itenerary_type);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($json_i_list);
        $list_of_iteneraries=array();
        while($stmt->fetch()){
            $list_of_iteneraries[]=json_decode($json_i_list);
        }
        $stmt->close();
        return $list_of_iteneraries;
    }

    public function setRowToTable($i_list, $date, $i_type, $table){
        $query="INSERT INTO $table VALUES ('', ?, ?, ?)";
        $stmt=$this->mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('sss', $i_list, $i_type, $date);
        $stmt->execute();
//        $stmt->store_result();
        if($stmt->fetch()!=null){
            $this->notification="Ваши данные успешно сохранены в Базу Данных";
        }
        else {$this->notification="К сожалению, не удалось сохранить ваш файл в Базу Данных";}
        $stmt->close();
    }

    public function setNewIteneraryType($i_type){
        $table="itenerary_type";
        $query="INSERT INTO $table VALUES ('', ?)";
        $stmt=$this->mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('s', $i_type);
        $stmt->execute();
//        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
    }
    public function getAllIteneraryType(){
        $table="itenerary_type";
        $query="SELECT i_type FROM $table";
        $stmt=$this->mysqli->stmt_init();
        $stmt->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($i_type);
        while($stmt->fetch()){
            $i_types[]=$i_type;
        }
        $stmt->close();
        return $i_types;
    }

}
