<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 02.02.16
 * Time: 15:05
 */

class StopTimeGenerator {

//    public $stop_time_array=array();
//    public $stop_distance_array=array();

    public $highway_velocity=0;
    public $town_velocity=0;
    public $start_time="";

    public $array_size=39;

    public function __construct($data_container, $user_date, $highway_velocity=150, $town_velocity=80){
        $this->array_size=count($data_container);
        if (is_string($user_date)){
            $this->start_time=$this->getUserDate($user_date);
        }
        else {
            $this->start_time=$user_date;
        }
        $this->highway_velocity=$highway_velocity;
        $this->town_velocity=$town_velocity;
    }

    private function getColumn($data_container, $column_position=6){
        $number_of_rows=count($data_container);
        for($i=0; $i<$number_of_rows; $i++){
            $row=$data_container[$i];
            $column[$i]=$row[$column_position];
            $column[$i]=trim($column[$i]);
        }
        return $column;
    }

    private function getDistances($summary_distances_column){
        $number_of_rows=count($summary_distances_column);
        $summary_distances_array=$summary_distances_column;
        for($i=1; $i<$number_of_rows; $i++) {
            if(substr_count($summary_distances_column[$i], "км") >=1){
                $summary_distances_column[$i]=str_replace(",", ".", $summary_distances_column[$i]);
                settype($summary_distances_column[$i], "float");
                $summary_distances_array[$i]=$summary_distances_column[$i];
            }
            else{
                settype($summary_distances_column[$i], "float");
                $summary_distances_array[$i]=round( ($summary_distances_column[$i]/1000), 1);
            }
        }
        return $summary_distances_array;
    }

    /*
     *  Данная функция возвращает массив расстояний до следующей остановки
     *  Используется, как внутренняя функция для рассчета скорости
     * */
    private function getRelativeDistances($summary_distances_array){
        // Для избежания ненужных ошибок создаем массив, с таким же количеством индексов
        $number_of_rows=count($summary_distances_array);
        $relative_distances_array=range(0, $number_of_rows-1);

        for($i=$number_of_rows-1; $i>=1; $i-- ){
            $next=$i+1;
            $relative_distances_array[$i]=$summary_distances_array[$next]-$summary_distances_array[$i];
            if($i==($number_of_rows-1)){$relative_distances_array[$i]=0;}
        }
        return $relative_distances_array;
    }

    private function getIntervals($intervals_column){
        $number_of_rows=count($intervals_column);
        for($i=1; $i<$number_of_rows; $i++){
            $intervals_column[$i]=trim($intervals_column[$i]);
            if(substr_count($intervals_column[$i], "мин") >=1){
                $intervals_column[$i]=trim(str_replace("сек", "", $intervals_column[$i]));
                $intervals_column[$i]=explode("мин", $intervals_column[$i]);
            }
            else{
                $intervals_column[$i]=trim(str_replace("сек", "", $intervals_column[$i]));
            }
            $intervals_array=$intervals_column;
        }
        return $intervals_array;
    }

    private function getVelocity($relative_distances_array){
        $number_of_rows=count($relative_distances_array);
        $velocity_array=range(0, $number_of_rows-1 );

        for($i=1; $i<$number_of_rows; $i++){
            if($relative_distances_array[$i]>=10){
                $velocity_array[$i]=$this->highway_velocity;
            }
            else{
                $velocity_array[$i]=$this->town_velocity;
            }
        }
        return $velocity_array;
    }


    public function getTimeOfStoppage($data_container){
        $converted_date_array['begin'][0]=$data_container[0][0];
        $converted_date_array['end'][0]=$data_container[0][1];

        $distances_column=$this->getColumn($data_container, 6);
        $summary_distances_array=$this->getDistances($distances_column);
        $relative_distances_array=$this->getRelativeDistances($summary_distances_array);

        $velocity_array=$this->getVelocity($relative_distances_array);

        $intervals_column=$this->getColumn($data_container, 2);
        $intervals_array=$this->getIntervals($intervals_column);

        $begin_time_array=range(0, $this->array_size-1);
        $end_time_array=range(0, $this->array_size-1);
//        $begin_time_array[1]=strtotime($this->start_time);
        $begin_time_array[1]=$this->start_time;

        if(count($intervals_array)!==count($velocity_array)){
            echo "array counts don't matches!";
        }
        else {
            for($i=1; $i<$this->array_size; $i++){
                $next=$i+1;
                if(is_array($intervals_array[$i])){
                    $intervals_array[$i]=60*$intervals_array[$i][0]+$intervals_array[$i][1];
                }
                $end_time_array[$i]=$begin_time_array[$i]+$intervals_array[$i];
                if($next!==$this->array_size){
                    if($relative_distances_array[$i]!==0 & $velocity_array[$i]!==0) {
                        $begin_time_array[$next] = $end_time_array[$i] + 3600 * $relative_distances_array[$i]/$velocity_array[$i];
                    }
                    else {
                        $begin_time_array[$next] =$begin_time_array[$i];
                    }
                }
                $converted_date_array['begin'][$i]=date("H:i:s d.m.Y", $begin_time_array[$i]);
                $converted_date_array['end'][$i]=date("H:i:s d.m.Y", $end_time_array[$i]);
            }
        }
        return $converted_date_array;
    }

//    public function insertColumnToArray($data_container=array(), $column=array(), $column_position=2){
//        $generated_data_array=$data_container;
//        if(count($data_container)!==$this->array_size){
//            echo "You insert an array with wrong size, please check!";
//        }
//        for($i=0; $i<$this->array_size; $i++){
//            $generated_data_array[$i][$column_position]=$column[$i];
//        }
//        return $generated_data_array;
//    }

    private function getUserDate($date){
        $date=trim($date);
        if($date==""){
            throw new Exception("В маршрутном листе не введена дата, проверьте правильность написания даты (вторая строка, первая колонка)");
        }
        $stamparray=preg_split("/[[:punct:][:space:]]/",$date);
        foreach($stamparray as $value){
            settype($value, 'integer');
        }
        $user_timestamp=mktime($stamparray[0], $stamparray[1],$stamparray[2],$stamparray[4],$stamparray[3],$stamparray[5]);
//        $realdata=date("Y-m-d",$stamp);
//        $this->start_time=$user_timestamp;
        return $user_timestamp;
    }

}




//    public function getDateOfStoppage($time_array){
//         $start_date=strtotime(date($this->start_date));
//        $begin_date_array=range(0,$this->start_date-1);
//        $end_date_array=$begin_date_array;
//        $begin_date_array[1]=$start_date;
//
//        for($i=1; $i<$this->array_size; $i++){
//            if(($start_date+(24*60*60))>$time_array['begin'][$i]){
//                $begin_date_array[$i]=$start_date;
//            }
//            else{
//                $begin_date_array[$i]=$start_date+(24*60*60);
//            }
//            if(($start_date+(24*60*60))>$time_array['end'][$i]){
//                $end_date_array[$i]=$start_date;
//            }
//            else{
//                $end_date_array[$i]=$start_date+(24*60*60);
//            }
//        }
//        $date_array['begin']=$begin_date_array;
//        $date_array['end']=$end_date_array;
//
//        return $date_array;
//
//    }
//
//    public function convertDateToFormat($time_array){
////        $date_array=$this->getDateOfStoppage($time_array);
//
//        for($i=1; $i<$this->array_size; $i++){
//            $converted_date_array['begin'][$i]=date("H:i:s d.m.Y", $time_array['begin'][$i]);
//            $converted_date_array['end'][$i]=date("H:i:s d.m.Y", $time_array['end'][$i]);
////            $time_array[$i]['begin']=date("H:i:s", $begin_time_array[$i]);
////            $time_array[$i]['end']=date("H:i:s", $end_time_array[$i]);
//        }
//        return $converted_date_array;
//    }