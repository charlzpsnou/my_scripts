<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 22.01.16
 * Time: 12:10
 */
//require_once("model.php");

Class formCreator{
    static protected $_form_creator=0;
    public $selected_year="";
    public $selected_month="";
    public $selected_day="";
    public $itenerary_type="";

    private function __construct(){
        $this->selected_year=date('Y');
        $this->selected_month=date('m');
        $this->selected_day=date('d');
    }

    static public function getFormCreator(){
        if(static::$_form_creator===0){
            static::$_form_creator=new self;
        }
        return static::$_form_creator;
    }

//    public function getIteneraryTypes(){
//        $enter_db = model::getDatabaseInstance();
//        $itenerary_types = $enter_db->getAllIteneraryType();
//        return $itenerary_types;
//    }

    public function getDateOptions(){
        $year=date('Y');
        settype($year, 'integer');
        for($i=1900; $i<=$year; $i++){
            $date_options['years'][$i]="$i";
        }
        for($i=1; $i<=12; $i++){
            if($i<10){
                $date_options['months'][$i]="0".$i;
            }
            else {
                $date_options['months'][$i]="$i";
            }

        }
        for($i=1; $i<=31; $i++){
            if($i<10){
                $date_options['days'][$i]="0".$i;
            }
            else {
                $date_options['days'][$i] = "$i";
            }
        }
        return $date_options;
    }
}