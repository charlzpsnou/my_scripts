<?php
////require_once("parser.php");
////require_once("ArrayParser.php");
////require_once("Session.php");
////require_once("StopTimeGenerator.php");
////require_once("formCreator.php");
//?>
<!--<html><head>-->
<!--    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />-->
<!--    <title>Страничка просмотра статистики</title>-->
<!--</head>-->
<!--<body>-->
<!---->
<!---->
<?php //if(isset($_REQUEST['doupload'])){
//    if(!copy($_FILES['myfile']['tmp_name'], "./tempfile2.csv")){
//        echo "Какие-то проблемы с загрузкой файла, возможно, файл не выбран";
//    }
//    elseif(!is_readable("./tempfile2.csv") ){
//        echo "Файл невозможно прочитать, какие-то проблемы с правами на чтение";
//    }
//    else {
//        $file_descriptor = fopen("./tempfile2.csv", 'r+');
//        if ($file_descriptor === false) {
//            echo "Не удалось открыть файл";
//        }
//        $data_container=array();
//        for ($i = 0; $data = fgetcsv($file_descriptor, 1000, ','); $i++) {
//            $num = count($data);
//            for ($k = 0; $k < $num; $k++) {
//                $data_container[$i][$k]=$data["$k"];
//            }
//        }
////        $output_date=mktime($_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);
//        $user_date=mktime($_REQUEST['hour'], $_REQUEST['min'], date('s'), $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);
//        $car_array=array("9523"=>"Ford_АА0309ЕВ", "17039"=>"VW T-5 AA2051OA ", "17040"=>"VW T-5 AA2052OA", "17041"=>"VW T-5 AA2480OP", "9524"=>"WV T-5_АА2729КМ" );
//
//
//        foreach($car_array as $key=>$value){
//            if($value==$_REQUEST['carname']){
//                $car_name=$_REQUEST['carname'];
//                $car_code=$key;
//            }
//        }
//
//        $session=Session::getInstance();
//        $session->data_container=$data_container;
//        $session->user_date=$user_date;
//        $session->car_name=$car_name;
//        $session->car_code=$car_code;
//        $session->town_velocity=$_REQUEST['town_velocity'];
//        $session->highway_velocity=$_REQUEST['highway_velocity'];
//    }
//}?>
<!--<form action="index.php" method="POST" enctype="multipart/form-data" name="doupload_form">-->
<!--    --><?php //$form_creator=formCreator::getFormCreator();
//    $date_options=$form_creator->getDateOptions();
//    $car_array=array("9523"=>"Ford_АА0309ЕВ", "17039"=>"VW T-5 AA2051OA ", "17040"=>"VW T-5 AA2052OA", "17041"=>"VW T-5 AA2480OP", "9524"=>"WV T-5_АА2729КМ" );
//    echo "Необходимо выбрать дату и время старта для генерации нового шаблона <br><br>";
//    ?>
<!--<table >-->
<!---->
<!--    <tr align="left">-->
<!--        <th  >Выберите дату начала маршрута</th>-->
<!--        <td>-->
<!--            <select name="year" required style="width: 70px" title="Год" >-->
<!--                --><?php
//                foreach($date_options['years'] as $year ){
//                    if ($year==date('Y')){
//                        echo '<option selected> '.$year."</option>";
//                    }
//                    else {
//                        echo '<option > '.$year."</option>";
//                    }
//                }
//                ?>
<!--            </select>-->
<!---->
<!--        </td>-->
<!--        <td><div> - </div></td>-->
<!--        <td>-->
<!--              <select name="month" required style="width: 70px" title="Месяц">-->
<!--                --><?php
//                foreach($date_options['months'] as $month ){
//                    if ($month==date('m')){
//                        echo '<option selected> '.$month."</option>";
//                    }
//                    else {
//                        echo '<option > '.$month."</option>";
//                    }
//                }
//                ?>
<!--            </select>-->
<!--        </td>-->
<!--        <td><div> - </div></td>-->
<!--        <td>-->
<!--            <select name="day" required style="width: 70px" title="День">-->
<!--                --><?php
//                foreach($date_options['days'] as $day){
//                    if ($day==date('d')){
//                        echo '<option selected> '.$day."</option>";
//                    }
//                    else {
//                        echo '<option > '.$day."</option>";
//                    }
//                }
//                ?>
<!--            </select>-->
<!--        </td>-->
<!--    </tr>-->
<!---->
<!--    <tr align="left">-->
<!--        <th>Выберите время начала маршрута</th>-->
<!--        <td>-->
<!--            <select name="hour" required style="width: 70px" title="Часы">-->
<!--                --><?php
//                for($hour=0; $hour<=24; $hour++){
//                    if ($hour==date('H')){
//                        echo '<option selected> '.$hour."</option>";
//                    }
//                    else {
//                        echo '<option > '.$hour."</option>";
//                    }
//                }
//                ?>
<!--            </select>-->
<!--        </td>-->
<!--        <td><div> : </div></td>-->
<!--        <td >-->
<!--            <select name="min" required style="width: 70px" title="Минуты">-->
<!--                --><?php
//                for($min=0; $min<=60; $min++){
//                    if ($min==date('i')){
//                        echo '<option selected> '.$min."</option>";
//                    }
//                    else {
//                        echo '<option > '.$min."</option>";
//                    }
//                }
//                ?>
<!--            </select>-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr align="left">-->
<!--        <th>Выберите автомобиль</th>-->
<!--        <td colspan="5">-->
<!--            <select name="carname" required style="width: 220px" title="Выбор автомобиля">-->
<!--                --><?php
//                echo '<option disabled selected> Выберите автомобиль </option>';
//                foreach($car_array as $car_code=>$car_name){
//                        echo '<option > '.$car_name.'</option>';
//                }
//                ?>
<!--            </select>-->
<!--        </td>-->
<!--    </tr>-->
<!---->
<!--    <tr>-->
<!--        <th> Введите среднюю скорость в городе</th>-->
<!--        <td colspan="3">-->
<!--            <input type="number" name="town_velocity" min="40" max="120" step="10" value="80">-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th> Введите среднюю скорость на трассе </th>-->
<!--        <td colspan="3">-->
<!--            <input type="number" name="highway_velocity" min="20" max="220" step="10" value="120">-->
<!--        </td>-->
<!--    </tr>-->
<!--</table>-->
<!--    <br><br><input type="file" name="myfile" placeholder="выберите шаблон с диска">-->
<!--    <input type="submit" name="doupload" value="Загрузить на сайт файл списка остановок на маршруте">-->
<!--</form>-->
<!---->
<!--<form action="index.php" method="POST" accept-charset="UTF-8" name="generate">-->
<!--    <input type="submit" name="generate" value="Сгенерировать маршрутный лист на основании шаблона">-->
<!--</form>-->
<!---->
<!--<table>-->
<!--    <tr>-->
<!--        <td>-->
<!--            --><?php //if(isset($_REQUEST['doupload']) || isset($_POST['generate'])){ ?>
<!--                <a href="temp.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */-->
<!--    display: inline-block;-->
<!--    padding: 5px 15px; /* Поля */-->
<!--    text-decoration: none; /* Убираем подчёркивание */-->
<!--    color: #0000FF; /* Цвет текста */">-->
<!--                    Сохранить файл на диск </a>-->
<!--            --><?php //}?>
<!--        </td>-->
<!--        <td style="width: 20px"></td>-->
<!--        <td>-->
<!--            --><?php //if(isset($_POST['generate'])){?>
<!--                <a href="temp_generated.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */-->
<!--    display: inline-block;-->
<!--    padding: 5px 15px; /* Поля */-->
<!--    text-decoration: none; /* Убираем подчёркивание */-->
<!--    color: #0000FF; /* Цвет текста */">-->
<!--                    Сохранить файл на диск (сгенерированный маршрут) </a>-->
<!---->
<!--            --><?php //}?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td>-->
<!--            --><?php //if(isset($_REQUEST['view_itenerary_list']) || isset($_REQUEST['doupload']) || isset($_POST['generate'])) {
//                $session = Session::getInstance();
//                if (isset($session->data_container)) {
//                    $data_container = $session->data_container;
//                    echo "<table border='1px' align=''>";
//                    foreach ($data_container as $row_number => $row) {
//                        echo "<tr>\n " ;
//                        foreach ($row as $col) {
//                            echo "<td style='height: 50px'><div style='font-size: small'>".$col."</div></td>";
//                        }
//                        echo "</tr>";
//                    }
//                    echo "</table>";
//                }
//            }?>
<!--        </td>-->
<!--        <td style="width: 20px"></td>-->
<!--        <td>-->
<!--            --><?php //if(isset($_POST['generate'])) {
//                $session = Session::getInstance();
//                if (isset($session->data_container)) {
//                    $data_container = $session->data_container;
//                    $user_date= $session->user_date;
//                    if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
//                        ob_start(
//                            null,
//                            0,
//                            PHP_OUTPUT_HANDLER_STDFLAGS ^
//                            PHP_OUTPUT_HANDLER_REMOVABLE
//                        );
//                    } else {
//                        ob_start(null, 0, false);
//                    }
//                    $array_parser = ArrayParser::createArrayParser();
//                    $intervals_column = $array_parser->parseColumn($data_container, 2);
//                    $new_intervals_column = $array_parser->generateIntervals($intervals_column, 20, 40);
//                    $generated_data_container = $array_parser->insertColumnToArray(
//                        $data_container,
//                        $new_intervals_column,
//                        2
//                    );
//
//                    $variable=new StopTimeGenerator($generated_data_container, $user_date, $session->highway_velocity, $session->town_velocity);
//                    $converted_date=$variable->getTimeOfStoppage($generated_data_container);
//
//
//                    $generated_data_container= $array_parser->insertColumnToArray(
//                        $generated_data_container,
//                        $converted_date['begin'],
//                        0
//                    );
//                    $generated_data_container= $array_parser->insertColumnToArray(
//                        $generated_data_container,
//                        $converted_date['end'],
//                        1
//                    );
//                    $last=count($converted_date['end'])-1;
////                    $converted_date['end'][1]=
////                    $converted_date['end'][$last]=
//
//                    echo "<table border='1px' align='' >";
////                    echo "<tr> $session->car_name </tr>";
//                    echo "<h2>Отчет по остановкам </h2>";
//                    echo "<h2>За период c  ".$variable->begin_date." по ".$variable->end_date."<h2>";
//                    echo "<h2>Для устройств  $session->car_code </h2>";
//                    echo "<h2>".$session->car_name." ( ".$session->car_code.")"."</h2>";
//                    foreach ($generated_data_container as $row_number => $row) {
//                        echo "<tr style='width: 50%'> \n";
//                        foreach ($row as $col) {
//                            echo "<td style='height: 50px'><div style='font-size: small'>".$col."</div></td>";
//                        }
//                        echo "</tr>";
//                    }
//                    echo "</table>";
//                    $output = ob_get_contents();
//                    ob_end_clean();
//                    if (!$file = fopen("temp_generated.html", "wb")) {
//                        echo "Не удалось сохранить файл";
//                    } else {
//                        $downloader = true;
//                        fwrite($file, iconv('UTF-8', 'Windows-1251', $output));
//                        fclose($file);
//                    }
//                }
//            }?>
<!--        </td>-->
<!--    </tr>-->
<!---->
<!--</table>-->
<!---->
<!--</body>-->
<!--</html>-->
<!---->
<?php
///**
// * Created by PhpStorm.
// * User: skrakovsky
// * Date: 22.01.16
// * Time: 12:10
// */
////require_once("model.php");
//
//Class formCreator{
//    static protected $_form_creator=0;
//    public $selected_year="";
//    public $selected_month="";
//    public $selected_day="";
//    public $car_array=array(
//        "9523"=>"Ford_АА0309ЕВ",
//        "17039"=>"VW T-5 AA2051OA ",
//        "17040"=>"VW T-5 AA2052OA",
//        "17041"=>"VW T-5 AA2480OP",
//        "9524"=>"WV T-5_АА2729КМ"
//    );
//
//    private function __construct(){
//        $this->selected_year=date('Y');
//        $this->selected_month=date('m');
//        $this->selected_day=date('d');
//    }
//
//    static public function getFormCreator(){
//        if(static::$_form_creator===0){
//            static::$_form_creator=new self;
//        }
//        return static::$_form_creator;
//    }
//
//
//    public function getDateOptions(){
//        $year=date('Y');
//        settype($year, 'integer');
//        for($i=1900; $i<=$year; $i++){
//            $date_options['years'][$i]="$i";
//        }
//        for($i=1; $i<=12; $i++){
//            if($i<10){
//                $date_options['months'][$i]="0".$i;
//            }
//            else {
//                $date_options['months'][$i]="$i";
//            }
//
//        }
//        for($i=1; $i<=31; $i++){
//            if($i<10){
//                $date_options['days'][$i]="0".$i;
//            }
//            else {
//                $date_options['days'][$i] = "$i";
//            }
//        }
//        return $date_options;
//    }
//}
//
//class ArrayParser {
//    static protected $date_generator=0;
//    private $user_date="";
//
//    private function __construct(){
//    }
//
//    public function parseColumn($itenerary_array=array(), $column_position=2){
//        $number_of_rows=count($itenerary_array);
//        for($i=0; $i<$number_of_rows; $i++){
//            $row=$itenerary_array[$i];
//            $column[$i]=$row[$column_position];
//            $column[$i]=trim($column[$i]);
//            if(substr_count($column[$i], "мин") >=1){
//                $column[$i]=trim(str_replace("сек", "", $column[$i]));
//                $column[$i]=explode("мин", $column[$i]);
//            }
//            else{
//                $column[$i]=trim(str_replace("сек", "", $column[$i]));
//            }
//
//        }
//        return $column;
//    }
//
//    public function generateIntervals($interval_column, $min_rand, $max_rand){
//        $intervals_number=count($interval_column);
//        for($i=1; $i<$intervals_number; $i++){
//            if(is_array($interval_column[$i])){
//                settype($interval_column[$i][0], "integer");
//                settype($interval_column[$i][1], "integer");
//                $interval_column[$i][0]=rand($min_rand, $max_rand)." мин";
//                $interval_column[$i][1]=rand(3,59)." сек";
//                $interval_column[$i]=$interval_column[$i][0]." ".$interval_column[$i][1];
//            }
//            else {
//                settype($interval_column[$i], "integer");
//                $interval_column[$i]=rand(3,59)." сек";
//            }
//
//        }
//        return $interval_column;
//    }
//
//    public function insertColumnToArray($data_array=array(), $column=array(), $column_position=2){
//        $new_data_array=$data_array;
//        $rows_number=count($data_array);
//        for($i=0; $i<$rows_number; $i++){
//            $new_data_array[$i][$column_position]=$column[$i];
//        }
//        return $new_data_array;
//    }
//
//
//    static public function createArrayParser(){
//        if (static::$date_generator===0){
//            static::$date_generator = new self;
//        }
//        return static::$date_generator;
//    }
//
//}
//
//class StopTimeGenerator {
//
////    public $stop_time_array=array();
////    public $stop_distance_array=array();
//
//    public $highway_velocity=0;
//    public $town_velocity=0;
//    public $start_time="";
//    public $begin_date="";
//    public $end_date="";
//
//    public $array_size=39;
//
//    public function __construct($data_container, $user_date, $highway_velocity=150, $town_velocity=80){
//        $this->array_size=count($data_container);
//        if (is_string($user_date)){
//            $this->start_time=$this->getUserDate($user_date);
//        }
//        else {
//            $this->start_time=$user_date;
//        }
//        $this->highway_velocity=$highway_velocity;
//        $this->town_velocity=$town_velocity;
//    }
//
//    private function getColumn($data_container, $column_position=6){
//        $number_of_rows=count($data_container);
//        for($i=0; $i<$number_of_rows; $i++){
//            $row=$data_container[$i];
//            $column[$i]=$row[$column_position];
//            $column[$i]=trim($column[$i]);
//        }
//        return $column;
//    }
//
//    private function getDistances($summary_distances_column){
//        $number_of_rows=count($summary_distances_column);
//        $summary_distances_array=$summary_distances_column;
//        for($i=1; $i<$number_of_rows; $i++) {
//            if(substr_count($summary_distances_column[$i], "км") >=1){
//                $summary_distances_column[$i]=str_replace(",", ".", $summary_distances_column[$i]);
//                settype($summary_distances_column[$i], "float");
//                $summary_distances_array[$i]=$summary_distances_column[$i];
//            }
//            else{
//                settype($summary_distances_column[$i], "float");
//                $summary_distances_array[$i]=round( ($summary_distances_column[$i]/1000), 1);
//            }
//        }
////        for($i=1; $i<$number_of_rows-1; $i++){
////            if($summary_distances_array[$i]<$summary_distances_array[$i-1]){
////                $summary_distances_array[$i]=($summary_distances_array[$i-1]+$summary_distances_array[$i+1])/2;
////            }
////        }
//        return $summary_distances_array;
//    }
//
//    /*
//     *  Данная функция возвращает массив расстояний до следующей остановки
//     *  Используется, как внутренняя функция для рассчета скорости
//     * */
//    private function getRelativeDistances($summary_distances_array){
//        // Для избежания ненужных ошибок создаем массив, с таким же количеством индексов
//        $number_of_rows=count($summary_distances_array);
//        $relative_distances_array=range(0, $number_of_rows-1);
//
//        for($i=$number_of_rows-1; $i>=1; $i-- ){
//            $next=$i+1;
//            $relative_distances_array[$i]=$summary_distances_array[$next]-$summary_distances_array[$i];
//            if($i==($number_of_rows-1)){$relative_distances_array[$i]=0;}
//        }
//        return $relative_distances_array;
//    }
//
//    private function getIntervals($intervals_column){
//        $number_of_rows=count($intervals_column);
//        for($i=1; $i<$number_of_rows; $i++){
//            $intervals_column[$i]=trim($intervals_column[$i]);
//            if(substr_count($intervals_column[$i], "мин") >=1){
//                $intervals_column[$i]=trim(str_replace("сек", "", $intervals_column[$i]));
//                $intervals_column[$i]=explode("мин", $intervals_column[$i]);
//            }
//            else{
//                $intervals_column[$i]=trim(str_replace("сек", "", $intervals_column[$i]));
//            }
//            $intervals_array=$intervals_column;
//        }
//        return $intervals_array;
//    }
//
//    private function getVelocity($relative_distances_array){
//        $number_of_rows=count($relative_distances_array);
//        $velocity_array=range(0, $number_of_rows-1 );
//
//        for($i=1; $i<$number_of_rows; $i++){
//            if($relative_distances_array[$i]>=10){
//                $velocity_array[$i]=$this->highway_velocity;
//            }
//            else{
//                $velocity_array[$i]=$this->town_velocity;
//            }
//        }
//        return $velocity_array;
//    }
//
//
//    public function getTimeOfStoppage($data_container){
//        $converted_date_array['begin'][0]=$data_container[0][0];
//        $converted_date_array['end'][0]=$data_container[0][1];
//
//        $distances_column=$this->getColumn($data_container, 6);
//        $summary_distances_array=$this->getDistances($distances_column);
//        $relative_distances_array=$this->getRelativeDistances($summary_distances_array);
//
//        $velocity_array=$this->getVelocity($relative_distances_array);
//
//        $intervals_column=$this->getColumn($data_container, 2);
//        $intervals_array=$this->getIntervals($intervals_column);
//
//        $begin_time_array=range(0, $this->array_size-1);
//        $end_time_array=range(0, $this->array_size-1);
////        $begin_time_array[1]=strtotime($this->start_time);
//        $begin_time_array[1]=$this->start_time;
//
//        if(count($intervals_array)!==count($velocity_array)){
//            echo "array counts don't matches!";
//        }
//        else {
//            for($i=1; $i<$this->array_size; $i++){
//                $next=$i+1;
//                if(is_array($intervals_array[$i])){
//                    $intervals_array[$i]=60*$intervals_array[$i][0]+$intervals_array[$i][1];
//                }
//                $end_time_array[$i]=$begin_time_array[$i]+$intervals_array[$i];
//                if($next!==$this->array_size){
//                    if($relative_distances_array[$i]!==0 & $velocity_array[$i]!==0) {
//                        $begin_time_array[$next] = $end_time_array[$i] + 3600 * $relative_distances_array[$i]/$velocity_array[$i];
//                    }
//                    else {
//                        $begin_time_array[$next] =$begin_time_array[$i];
//                    }
//                }
//                $converted_date_array['begin'][$i]=date("H:i:s d.m.Y", $begin_time_array[$i]);
//                $converted_date_array['end'][$i]=date("H:i:s d.m.Y", $end_time_array[$i]);
//            }
//            $this->begin_date=date("d.m.Y", $begin_time_array[1]);
//            $this->end_date=date("d.m.Y", $end_time_array[($this->array_size-1)]);
//        }
//        return $converted_date_array;
//    }
//
//    private function getUserDate($date){
//        $date=trim($date);
//        if($date==""){
//            throw new Exception("В маршрутном листе не введена дата, проверьте правильность написания даты (вторая строка, первая колонка)");
//        }
//        $stamparray=preg_split("/[[:punct:][:space:]]/",$date);
//        foreach($stamparray as $value){
//            settype($value, 'integer');
//        }
//        $user_timestamp=mktime($stamparray[0], $stamparray[1],$stamparray[2],$stamparray[4],$stamparray[3],$stamparray[5]);
////        $realdata=date("Y-m-d",$stamp);
////        $this->start_time=$user_timestamp;
//        return $user_timestamp;
//    }
//}
//
//
//class Session
//{
//    const SESSION_STARTED = TRUE;
//    const SESSION_NOT_STARTED = FALSE;
//
//    // The state of the session
//    private $sessionState = self::SESSION_NOT_STARTED;
//
//    // THE only instance of the class
//    static private  $instance;
//
//
//    private function __construct() {}
//
//
//    /**
//     *    Стандарный синглтон. Хотя лучше его не использовать
//     *    Метод возвращает свойство класса, в котором ссылка на объект класса
//     *    Если Объекта нет, то он создается и помещается в свойство класса
//     *
//     *    @return    object
//     **/
//
//    public static function getInstance()
//    {
//        if ( !isset(self::$instance))
//        {
//            self::$instance = new self;
//        }
//
//        self::$instance->startSession();
//
//        return self::$instance;
//    }
//
//
//    /**
//     *    (Re)starts the session.
//     *
//     *    @return    bool    TRUE if the session has been initialized, else FALSE.
//     **/
//
//    public function startSession()
//    {
//        if ( $this->sessionState == self::SESSION_NOT_STARTED )
//        {
//            $this->sessionState = session_start();
//        }
//
//        return $this->sessionState;
//    }
//
//
//    /**
//     *    Stores data in the session.
//     *    Example: $instance->foo = 'bar';
//     *
//     *    @param    name    Name of the data.
//     *    @param    value    Your data.
//     *    @return    void
//     **/
//
//    public function __set( $name , $value )
//    {
//        $_SESSION[$name] = $value;
//
//    }
//
//
//    /**
//     *    Gets datas from the session.
//     *    Example: echo $instance->foo;
//     *
//     *    @param    name    Name of the datas to get.
//     *    @return    mixed    Datas stored in session.
//     **/
//
//    public function __get( $name )
//    {
//        if ( isset($_SESSION[$name]))
//        {
//            return $_SESSION[$name];
//        }
//    }
//
//
//    public function __isset( $name )
//    {
//        return isset($_SESSION[$name]);
//    }
//
//
//    public function __unset( $name )
//    {
//        unset( $_SESSION[$name] );
//    }
//
//
//    /**
//     *    Destroys the current session.
//     *
//     *    @return    bool    TRUE is session has been deleted, else FALSE.
//     **/
//
//    public function destroy()
//    {
//        if ( $this->sessionState == self::SESSION_STARTED )
//        {
//            $this->sessionState = !session_destroy();
//            unset( $_SESSION );
//
//            return !$this->sessionState;
//        }
//
//        return FALSE;
//    }
//}