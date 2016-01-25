<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 22.01.16
 * Time: 12:10
 */
require_once("model.php");

Class formCreator{
    static protected $_form_creator=0;
//    private $selected_year="2000";
//    private $selected_month="01";
//    private $selected_day="01";

    private function __construct(){
    }

    public function create_itenerary_type_options(){
            $enter_db = model::getDatabaseInstance();
            $itenerary_types = $enter_db->getAllIteneraryType();
            foreach ($itenerary_types as $value) {
                echo "<option> ".$value."</option>";
            }
    }
    public function create_years_options(){
        for($year=2000;$year<=2100; $year++){
            if($year==$_REQUEST['year']){
                echo '<option selected> '.$year."</option>"; }
            else {
                echo '<option > '.$year."</option>"; }
        }
    }

    public function create_months_options(){
        for($month=1;$month<=12; $month++){
            if($month<10){ $monthe="0".$month;}
            else {$monthe=$month;}
            if($monthe==($_REQUEST['month'])){
                echo "<option selected>".$monthe."</option>";
            }
            else {
                echo "<option >".$monthe."</option>";
            }
        }
    }

    public function create_days_options(){
        for($day=1;$day<=31; $day++){
            if($day<10){ $days="0".$day;}
                else { $days=$day;}
            if($days==($_REQUEST['day'])){
                echo '<option selected> '.$days."</option>";
            }
            else { echo '<option> '.$days."</option>"; }
        }
    }

//    public function save_choise($selected_year, $selected_month, $selected_day){
//        $this->selected_year=$selected_year;
//        $this->selected_monthe=$selected_month;
//        $this->selected_day=$selected_day;
//    }

    static public function getFormCreator(){
        if(static::$_form_creator===0){
            static::$_form_creator=new formCreator();
        }
        return static::$_form_creator;
    }
}