<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 21.01.16
 * Time: 14:36
 */
require_once("model.php");

Class parser{
    private $file_source;
    private $itenerary_type;

    public function __construct($f, $itenerary_type){
        $this->file_source=$f;
        $this->itenerary_type=$itenerary_type;
    }

    public function parseCSV(){
        $itinerary_array=array();
        for ($i = 0; $data = fgetcsv($this->file_source, 1000, ','); $i++) {
            $num = count($data);
            for ($k = 0; $k < $num; $k++) {
                $itinerary_array[$i][$k]=$data["$k"];
            }
        }
        $this->_saveIteneraryListToDB($itinerary_array);
        return $itinerary_array;
    }

//    public function parseColumn($itenerary_array, $column_number){
//        foreach($itenerary_array as $row){
//            $column[]=$row[$column_number];
//        }
//        return $column;
//    }
//    public function getDistances($itenerary_array, $column_number){
//        foreach($itenerary_array as $index=>$row){
//            if($index===0){
//                continue;
//            }
//                $column[]=trim($row[$column_number]);
//        }
//        foreach($column as $key=>$value){
//            settype($column[$key], 'integer');
//        }
//        return $column;
//    }

    private function _saveIteneraryListToDB($itinerary_array){
        $i_date=$this->getDateFromIteneraryList($itinerary_array);
        $json_i_list=json_encode($itinerary_array);
        $i_type=$this->itenerary_type;
        $enter_db=model::getDatabaseInstance();
        $if_save=$enter_db->setRowToTable($json_i_list, $i_date, $i_type,"itenerary");
    }

    private function getDateFromIteneraryList($itinerary_array){
        //Берем дату из первой колонки второй строки и далее преобразуем ее в формат для Базы Данных
        $date=$itinerary_array[1][0];
        $date=trim($date);
        if($date==""){
            throw new Exception("В маршрутном листе не введена дата, проверьте правильность написания даты (вторая строка, первая колонка)");
        }
        $stamparray=preg_split("/[[:punct:][:space:]]/",$date);
//    var_dump($stamparray);
        foreach($stamparray as $value){
            settype($value, 'integer');
        }
        $stamp=mktime($stamparray[0], $stamparray[1],$stamparray[2],$stamparray[4],$stamparray[3],$stamparray[5]);
        $realdata=date("Y-m-d",$stamp);
        return $realdata;
    }
}