<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 16.02.16
 * Time: 15:49
 */
?>
<?php if( isset($_REQUEST['upload']) ){
    $files_reader= new files_Reader($_FILES['userfile']);
    $containers_list=$files_reader->getContainers();
//        echo "<pre>"; var_dump($containers_list); echo "</pre>";
    foreach($containers_list as $file_name => $data_container){
        $counter+=1;
        //Проверяем скорости
        $checker= new dataCheck($data_container);
        $checker->checkDistances();

        //Проверяем адреса
        $checker->checkAddress();

        $checker->checkStopaggesTime();

        $errors=$checker->getErrors();
        echo "Файл # $counter : $file_name <br> <pre>"; var_dump($errors); echo "</pre>";
        unset($checker);
    }
}
?>

<html>
    <body>
    <form action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="post" enctype="multipart/form-data">
        Файлы:<br />
            <input name="userfile[]" type="file" multiple /><br />
        <input type="submit" name="upload" value="Отправить" />
    </form>
    </body>
</html>


<?php
class dataCheck {

    protected $data_container;
    protected $new_data_container;

    protected $array_size;

    protected $data;
    protected $speed_column;

    protected $error_list=array();

    public function __construct($data_container){
        $this->data_container=$data_container;
        $this->array_size=count($data_container);
        $this->data=$data_container;
        $this->new_data_container=$data_container;
    }

    public function getErrors(){
        if(empty($this->error_list)){
            return "Ошибки не найдены";
        }
        return $this->error_list;
    }

    public function checkDistances($column_position=6){
        $summary_distances_column=array_column($this->data_container, $column_position);
        for($i=1; $i<$this->array_size; $i++) {
            if(substr_count($summary_distances_column[$i], "км") >=1){
                $summary_distances_column[$i]=str_replace(",", ".", $summary_distances_column[$i]);
                settype($summary_distances_column[$i], "float");
                $this->data[$i][$column_position]=$summary_distances_column[$i];
            }
            else{  // если у нас не расстояние не в км., а в метрах "м"
                settype($summary_distances_column[$i], "float");
                $this->data[$i][$column_position]=round( ($summary_distances_column[$i]/1000), 1);
            }
            if( ($this->data[$i][$column_position]) < ($this->data[($i-1)][$column_position]) ){
                $this->error_list['distances'][$i]="Неправильно задана дистанция в строке $i со значением :".$this->data[$i][$column_position];
            }
        }
        return  $this->data;
    }

    public function checkAddress($column_position=4){
        $summary_addresses_column=array_column($this->data_container, $column_position);
        for($i=1; $i<$this->array_size; $i++) {
            if(substr_count($summary_addresses_column[$i], "Украина, ") < 1){
                $this->error_list['address'][$i].="некорректно задана страна в строке ".($i+1)." со значением :".$summary_addresses_column[$i]." - ";
            }
            if(substr_count($summary_addresses_column[$i], "обл., ") < 1){
                if(substr_count($summary_addresses_column[$i], "г. Киев,") < 1)
                $this->error_list['address'][$i].="некорректно задана область или г. Киев в строке ".($i+1)." со значением :".$summary_addresses_column[$i]." - ";
            }
            if(substr_count($summary_addresses_column[$i], "г. ") < 1 &&
                substr_count($summary_addresses_column[$i], "пгт. ") < 1 &&
                substr_count($summary_addresses_column[$i], "п. ") < 1 &&
                substr_count($summary_addresses_column[$i], "с. ") < 1){
                $this->error_list['address'][$i].="некорректно задан город/поселок/село в строке ".($i+1)." со значением :".$summary_addresses_column[$i];
            }
            if( preg_match('/.*[[:punct:]]$/i', $summary_addresses_column[$i]) ){
                $this->error_list['address'][$i].="стоит запятая вконце строки ".($i+1)." со значением :".$summary_addresses_column[$i];
            }
        }
    }

    public function checkStopaggesTime($column_position=2){
        for($i=1; $i<$this->array_size; $i++){
            $row=$this->data_container[$i];
            $interval_column[$i]=$row[$column_position];
            $interval_column[$i]=trim(str_replace("сек", "", $interval_column[$i]));
            if(substr_count($interval_column[$i], "мин") >=1){
                $interval_column[$i]=explode("мин", $interval_column[$i]);
                if($interval_column[$i][0]<15){
                    $interval_column[$i][0]=rand(1, 3);
                }
                else{
                    $interval_column[$i][0]=rand(20, 40);
                }
                $interval_column[$i][1]=rand(3,59);
            }
            else{
                $interval_column[$i]=rand(3,59);
            }
            $this->data[$i][$column_position]=$interval_column[$i];
        }
        $counter=0;
        foreach($interval_column as $interval){
            if(is_array($interval)){
                if($interval[0]>=20){ $counter++;}
            }
        }
        if($counter>=0){
            $this->error_list['stops'].="Превышено количество остановок. Количество остановок более чем на 20 минут = ".$counter."<br>";
        }
    }
}

Class files_Reader{

    private $containers_list=array();
    private $file;
    private $file_descriptor;
    private $temp_filename="tempfile.csv";
    public $data_container=array();

    public function __construct($files){
        for($i=0; $i<count($files['tmp_name']); $i++){
            $file=$files['tmp_name'][$i];
            $name=$files['name'][$i];
            $this->containers_list[$name]=$this->readCSVFile($file);
        }
//        foreach($files['tmp_name'] as $file){
//            foreach($files['name'] as $name){
//                $this->containers_list[$name]=$this->readCSVFile($file);
//            }
//        }
    }

    public function getContainers(){
        return $this->containers_list;
    }

    public function readCSVFile($file){
        if(!copy($file, $this->temp_filename)){
            echo "Какие-то проблемы с загрузкой файла, возможно, файл не выбран <br>";
        }
        elseif(!is_readable($this->temp_filename) ){
            echo "Файл невозможно прочитать, какие-то проблемы с правами на чтение <br>";
            return false;
        }
        $this->file_descriptor = fopen($this->temp_filename, 'r+');
        if ($this->file_descriptor === false) {
            echo "Не удалось открыть файл <br>";
        }
        for ($i = 0; $data = fgetcsv($this->file_descriptor, 1000, ','); $i++) {
            $num = count($data);
            for ($k = 0; $k < $num; $k++) {
                $this->data_container[$i][$k]=$data["$k"];
            }
        }
        fclose($this->file_descriptor);
        $data=$this->data_container;
        $this->data_container=array();
        return $data;
    }
}
