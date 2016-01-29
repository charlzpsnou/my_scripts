<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 29.01.16
 * Time: 10:34
 */

class DateGenerator {
    static protected $date_generator=0;
//    private $current_year="";
//    private $current_month="";
//    private $current_day="";
    private $user_date="";

    private function __construct(){
        $this->current_year=date('Y');
        $this->current_month=date('m');
        $this->current_day=date('d');
    }

    public function generateStartDateColumn($user_date=array()){
        if(empty($user_date)){
            $this->user_date=date("H:i:s d.m.Y");
        }
        else{
            $this->user_date=date("H:i:s d.m.Y", mktime($user_date['H'], $user_date['i'], $user_date['s'], $user_date['m'], $user_date['d'], $user_date['Y']));
        }
        return $this->user_date;
    }

    public function parseColumn($itenerary_array=array(), $column_position=2){
        $number_of_rows=count($itenerary_array);
        for($i=0; $i<$number_of_rows; $i++){
            $row=$itenerary_array[$i];
            $column[$i]=$row[$column_position];
            $column[$i]=trim($column[$i]);
            if(substr_count($column[$i], "мин") >=1){
                $column[$i]=trim(str_replace("сек", "", $column[$i]));
                $column[$i]=explode("мин", $column[$i]);
            }
            else{
                $column[$i]=trim(str_replace("сек", "", $column[$i]));
            }

        }
        return $column;
    }

    public function generateIntervals($interval_column, $min_rand, $max_rand){
        $intervals_number=count($interval_column);
        for($i=2; $i<$intervals_number; $i++){
            if(is_array($interval_column[$i])){
                settype($interval_column[$i][0], "integer");
                settype($interval_column[$i][1], "integer");
                $interval_column[$i][0]=rand($min_rand, $max_rand)." мин";
                $interval_column[$i][1]=rand(3,59)." сек";
                $interval_column[$i]=$interval_column[$i][0]." ".$interval_column[$i][1];
            }
            else {
                settype($interval_column[$i], "integer");
                $interval_column[$i]=rand(3,59)." сек";
            }

        }
        return $interval_column;
    }

    public function insertColumnToArray($data_array=array(), $column=array(), $column_position=2){
        $new_data_array=$data_array;
        $rows_number=count($data_array);
        for($i=0; $i<$rows_number; $i++){
            $new_data_array[$i][$column_position]=$column[$i];
        }
        return $new_data_array;
    }

//    private function random($number){
//        $max_rand=$number/2;
//        $rand_number=$number+rand(1, $max_rand);
//        if($rand_number>=59){
//            $number-=rand(1, $max_rand);
//        }
//        else{
//            $number+=rand(1, $max_rand);
//        }
//        return $number;
//    }




    static public function createDateGenerator(){
        if (static::$date_generator===0){
            static::$date_generator = new DateGenerator();
        }
        return static::$date_generator;
    }

}