<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 08.02.16
 * Time: 8:11
 */
?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Страничка генерации путевых листов</title>
</head>
<body>

<?php if(isset($_REQUEST['doupload'])){
    $file_reader= new fileReader($_FILES['myfile']['tmp_name']);
    $data_container=$file_reader->readCSVFile();

    $form_options= new formOptionsCreator();
    foreach($form_options->car_array as $car_code=>$car_name){
        if($car_name==$_REQUEST['carname']){
            $user_input['car_name']=$_REQUEST['carname'];
            $user_input['car_code']=$car_code;
        }
    }
    $user_input['user_date']=mktime($_REQUEST['hour'], $_REQUEST['min'], date('s'), $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);
    $user_input['town_velocity']=$_REQUEST['town_velocity'];
    $user_input['highway_velocity']=$_REQUEST['highway_velocity'];

    $session=Session::getInstance();
    $session->data_container=$data_container;
    $session->user_input=$user_input;
    }
?>

<form action="newindex.php" method="POST" enctype="multipart/form-data" name="doupload_form">
    <?php $formOptionsCreator= new formOptionsCreator();
    $date_options=$formOptionsCreator->getDateOptions();
    $car_array=$formOptionsCreator->car_array;
    ?>
    <div> Необходимо выбрать автомобиль, дату и время выезда, среднюю скорость на трассе и в городе.
        После этого можно загрузить шаблон для генерации нового путевого листа </div>
    <table >
        <tr align="left">
            <th  >Выберите дату начала маршрута</th>
            <td>
                <select name="year" required style="width: 70px" title="Год" >
                    <?php
                    $formOptionsCreator->optionsOutput($date_options['year'], date('Y'));
                    ?>
                </select>
            </td>
            <td><div> - </div></td>
            <td>
                <select name="month" required style="width: 70px" title="Месяц">
                    <?php
                    $formOptionsCreator->optionsOutput($date_options['month'], date('m'));
                    ?>
                </select>
            </td>
            <td><div> - </div></td>
            <td>
                <select name="day" required style="width: 70px" title="День">
                    <?php
                    $formOptionsCreator->optionsOutput($date_options['day'], date('d'));
                    ?>
                </select>
            </td>
        </tr>

        <tr align="left">
            <th>Выберите время начала маршрута</th>
            <td>
                <select name="hour" required style="width: 70px" title="Часы">
                    <?php
                    $formOptionsCreator->optionsOutput($date_options['hour'], date('H'));
                    ?>
                </select>
            </td>
            <td><div> : </div></td>
            <td >
                <select name="min" required style="width: 70px" title="Минуты">
                    <?php
                    $formOptionsCreator->optionsOutput($date_options['min'], date('i'));
                    ?>
                </select>
            </td>
        </tr>
        <tr align="left">
            <th>Выберите автомобиль</th>
            <td colspan="5">
                <select name="carname" required style="width: 220px" title="Выбор автомобиля">
                    <?php
                    echo '<option disabled selected> Выберите автомобиль </option>';
                    foreach($car_array as $car_code=>$car_name){
                        echo '<option > '.$car_name.'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <th> Введите среднюю скорость в городе</th>
            <td colspan="3">
                <input type="number" name="town_velocity" min="40" max="120" step="10" value="80">
            </td>
        </tr>
        <tr>
            <th> Введите среднюю скорость на трассе </th>
            <td colspan="3">
                <input type="number" name="highway_velocity" min="20" max="220" step="10" value="120">
            </td>
        </tr>
    </table>
    <br><br><input type="file" name="myfile" placeholder="выберите шаблон с диска">
    <input type="submit" name="doupload" value="Загрузить на сайт файл списка остановок на маршруте">
</form>

<form action="newindex.php" method="POST" accept-charset="UTF-8" name="generate">
    <input type="submit" name="generate" value="Сгенерировать маршрутный лист на основании шаблона">
</form>

</body>
</html>

    <table>
        <tr>
            <td>
                <?php if(isset($_REQUEST['doupload']) || isset($_POST['generate'])){ ?>
                    <a href="temp.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #0000FF; /* Цвет текста */">
                        Сохранить файл на диск </a>
                <?php }?>
            </td>
            <td style="width: 20px"></td>
            <td>
                <?php if(isset($_POST['generate'])){?>
                    <a href="temp_generated.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #0000FF; /* Цвет текста */">
                        Сохранить файл на диск (сгенерированный маршрут) </a>

                <?php }?>
            </td>
        </tr>
        <tr>
            <td>
                <?php if(isset($_POST['doupload']) || isset($_POST['generate'])) {
                    $session = Session::getInstance();
                    if (isset($session->data_container)) {
                        $data_container = $session->data_container;
                        echo "<table border='1px' align=''>";
                        foreach ($data_container as $row_number => $row) {
                            echo "<tr>\n " ;
                            foreach ($row as $col) {
                                echo "<td style='height: 50px'><div style='font-size: small'>".$col."</div></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                }?>
            </td>
            <td style="width: 20px"></td>
            <td>
                <?php if(isset($_POST['generate'])) {
                    $session = Session::getInstance();
                    if (isset($session->data_container)) {
                        $data_container = $session->data_container;
                        $user_date= $session->user_input['user_date'];
                        $highway_velocity=$session->user_input['highway_velocity'];
                        $town_velocity=$session->user_input['town_velocity'];

                        $data_generator= new dataGenerator($data_container, $user_date, $highway_velocity, $town_velocity);
                        try{
                            $generated_data_container=$data_generator->generateData();
                        }
                        catch(Exception $e){
                            echo $e->getMessage();
                        }

                        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
                            ob_start(
                                null,
                                0,
                                PHP_OUTPUT_HANDLER_STDFLAGS ^
                                PHP_OUTPUT_HANDLER_REMOVABLE
                            );
                        } else {
                            ob_start(null, 0, false);
                        }
                        echo "<table border='1px' align='' >";
                        echo "<h2>Отчет по остановкам </h2>";
                        echo "<h2>За период c  ".$data_generator->begin_date." по ".$data_generator->end_date."<h2>";
                        echo "<h2>Для устройств  $session->car_code </h2>";
                        echo "<h2>".$session->user_input['car_name']." ( ".$session->user_input['car_code'].")"."</h2>";
                        foreach ($generated_data_container as $row_number => $row) {
                            echo "<tr style='width: 50%'> \n";
                            foreach ($row as $col) {
                                echo "<td style='height: 50px'><div style='font-size: small'>".$col."</div></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                        $output = ob_get_contents();
                        ob_end_clean();
                        if (!$file = fopen("temp_generated.html", "wb")) {
                            echo "Не удалось сохранить файл";
                        } else {
                            fwrite($file, iconv('UTF-8', 'UTF-8', $output));
                            fclose($file);
                        }
                    }
                }?>
            </td>
        </tr>

    </table>






<?php
/**
 * Created by PhpStorm.
 * User: skrakovsky
 * Date: 22.01.16
 * Time: 12:10
 */
class dataGenerator {

    protected $data_container;
    protected $highway_velocity;
    protected $town_velocity;
    protected $start_time;
    protected $max_random;
    protected $min_random;
    protected $array_size;

    protected $data;
    protected $speed_column;

    public $begin_date="";
    public $end_date="";

    public function __construct($data_container, $user_date, $highway_velocity=150, $town_velocity=80, $max_random=40, $min_random=20){
        $this->data_container=$data_container;
        $this->array_size=count($data_container);
        $this->data=$data_container;
        $this->highway_velocity=$highway_velocity;
        $this->town_velocity=$town_velocity;
        $this->max_random=$max_random;
        $this->min_random=$min_random;

        if (is_string($user_date)){
            $this->start_time=$this->getUserDate($user_date);
        }
        else {
            $this->start_time=$user_date;
        }
    }

    public function generateData(){
//        $data=&$this->internal_array;
        $start_time_pos=0;
        $end_time_pos=1;
        $intervals_pos=2;
        $relative_dist_pos=3;
        $dist_pos=6;

        $this->generateIntervalsColumn();
        $this->getDistances();
        $this->calculateRelativeDistances();
        $this->calculateSpeed();

        for($i=1; $i<$this->array_size; $i++){
            if($i===1){
                $this->data[1][$start_time_pos]=$this->start_time;
                if(is_array($this->data[1][$intervals_pos])){
                    $this->data[1][$end_time_pos]=$this->data[1][$start_time_pos]+(60*$this->data[1][$intervals_pos][0]+$this->data[1][$intervals_pos][0]);
                }
                else{
                    $this->data[1][$end_time_pos]=$this->data[1][$start_time_pos]+$this->data[1][$intervals_pos];
                }
            }
            else {
                $this->data[$i][$start_time_pos] = $this->data[($i - 1)][$end_time_pos] + round(3600*($this->data[$i][$relative_dist_pos] / $this->speed_column[($i - 1)]));
                if(is_array($this->data[$i][$intervals_pos])){
                    $this->data[$i][$end_time_pos] = $this->data[$i][$start_time_pos] + ( 60*$this->data[$i][$intervals_pos][0]+$this->data[$i][$intervals_pos][1] );
                }
                else{
                    $this->data[$i][$end_time_pos] = $this->data[$i][$start_time_pos] + $this->data[$i][$intervals_pos];
                }
            }
        }
        $this->begin_date=date("Y.m.d",$this->data[1][0]);
        $this->end_date=date("Y.m.d", $this->data[($this->array_size-1)][1]);
        $this->prepareDataForOutput();
        return $this->data;
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

    private function generateIntervalsColumn( $column_position=2){
        for($i=1; $i<$this->array_size; $i++){
            $row=$this->data_container[$i];
            $interval_column[$i]=$row[$column_position];
            $interval_column[$i]=trim(str_replace("сек", "", $interval_column[$i]));
            if(substr_count($interval_column[$i], "мин") >=1){
                $interval_column[$i]=explode("мин", $interval_column[$i]);
                if($interval_column[$i][0]<5){
                    $interval_column[$i][0]=rand(1, $interval_column[$i][0]);
                }
                else{
                    $interval_column[$i][0]=rand($this->min_random, $this->max_random);
                }
                $interval_column[$i][1]=rand(3,59);
            }
            else{
                $interval_column[$i]=rand(3,59);
            }
            $this->data[$i][$column_position]=$interval_column[$i];
        }
        return $interval_column;
    }

    private function getDistances($column_position=6){
        $summary_distances_column=$this->getColumn($this->data_container, $column_position);
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
                Throw new Exception("В шаблоне неправильно задано расстояние в строке $i");
            }
        }
        return  $this->data;
    }

    private function calculateRelativeDistances($column_position=3){
        $summary_distances_column=$this->getColumn($this->data, 6);
        for($i=1; $i<$this->array_size; $i++){
            if($i===1){
                $this->data[1][$column_position]=0; // Нулевая точка. У нее нет расстояния до предыдущей.
            }
            else{
                $this->data[$i][$column_position]=round($summary_distances_column[$i]-$summary_distances_column[($i-1)], 1);
            }
        }
    }

    private function calculateSpeed(){
        for($i=1; $i<$this->array_size; $i++) {
            if($this->data[($i+1)][3]<10){
                $this->speed_column[$i]=$this->town_velocity;
            }
            else{
                $this->speed_column[$i]=$this->highway_velocity;
            }
        }
    }

    private function prepareDataForOutput(){
        for($i=1; $i<$this->array_size; $i++){
            $this->data[$i][0]=date("H:i:s d.m.Y", $this->data[$i][0]);
            $this->data[$i][1]=date("H:i:s d.m.Y", $this->data[$i][1]);
            if( is_array($this->data[$i][2]) ){
                $this->data[$i][2]=$this->data[$i][2][0]." мин ".$this->data[$i][2][1]." сек";
            }
            else{
                $this->data[$i][2].=" сек";
            }
            if($this->data[$i][3]<1){
                $this->data[$i][3].=" м";
            }
            else{
                $this->data[$i][3].=" км";
            }
            if($this->data[$i][6]<1){
                $this->data[$i][6]=($this->data[$i][6]*1000)." м";
            }
            else{
                $this->data[$i][6].=" км";
            }
        }
    }

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
        return $user_timestamp;
    }
}


Class formOptionsCreator{

    public $car_array=array(
        "9523"=>"Ford_АА0309ЕВ",
        "17039"=>"VW T-5 AA2051OA ",
        "17040"=>"VW T-5 AA2052OA",
        "17041"=>"VW T-5 AA2480OP",
        "9524"=>"WV T-5_АА2729КМ"
    );

    public function __construct(){
    }

    public function optionsOutput($options, $selected_option){
        foreach($options as $option ){
            if ($option==$selected_option){
                echo '<option selected> '.$option."</option>";
            }
            else {
                echo '<option > '.$option."</option>";
            }
        }
    }

    public function getDateOptions(){
        $date_options=array();
        $year=date('Y');
        settype($year, 'integer');
        for($i=1900; $i<=$year; $i++){
            $date_options['year'][$i]="$i";
        }
        for($i=1; $i<=12; $i++){
            if($i<10){
                $date_options['month'][$i]="0".$i;
            }
            else {
                $date_options['month'][$i]="$i";
            }

        }
        for($i=1; $i<=31; $i++){
            if($i<10){
                $date_options['day'][$i]="0".$i;
            }
            else {
                $date_options['day'][$i] = "$i";
            }
        }
        for($hour=0; $hour<=24; $hour++) {
            if ($hour < 10) {
                $date_options['hour'][$hour]="0".$hour;
            }
            else{
                $date_options['hour'][$hour]="$hour";
            }
        }
        for($min=0; $min<=60; $min++){
            if($min<10){
                $date_options['min'][$min]="0".$min;
            }
            else{
                $date_options['min'][$min]="$min";
            }
        }
        return $date_options;
    }
}

Class fileReader{

    private $file;
    private $file_descriptor;
    private $temp_filename="tempfile.csv";
    public $data_container=array();

    public function __construct($file){
        $this->file=$file;
    }

    public function readCSVFile(){
        if(!copy($this->file, $this->temp_filename)){
            echo "Какие-то проблемы с загрузкой файла, возможно, файл не выбран";
        }
        elseif(!is_readable($this->temp_filename) ){
            echo "Файл невозможно прочитать, какие-то проблемы с правами на чтение";
            return false;
        }
        $this->file_descriptor = fopen($this->temp_filename, 'r+');
        if ($this->file_descriptor === false) {
            echo "Не удалось открыть файл";
        }
        for ($i = 0; $data = fgetcsv($this->file_descriptor, 1000, ','); $i++) {
            $num = count($data);
            for ($k = 0; $k < $num; $k++) {
                $this->data_container[$i][$k]=$data["$k"];
            }
        }
        fclose($this->file_descriptor);
        return $this->data_container;
    }
}
class Session{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;

    // The state of the session
    private $sessionState = self::SESSION_NOT_STARTED;

    // THE only instance of the class
    static private  $instance;


    private function __construct() {}

    /**
     *    Стандарный синглтон. Хотя лучше его не использовать
     *    Метод возвращает свойство класса, в котором ссылка на объект класса
     *    Если Объекта нет, то он создается и помещается в свойство класса
     *
     *    @return    object
     **/

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }


    /**
     *    (Re)starts the session.
     *
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/

    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }


    /**
     *    Stores data in the session.
     *    Example: $instance->foo = 'bar';
     *
     *    @param    name    Name of the data.
     *    @param    value    Your data.
     *    @return    void
     **/

    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;

    }


    /**
     *    Gets datas from the session.
     *    Example: echo $instance->foo;
     *
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/

    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }


    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }


    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }


    /**
     *    Destroys the current session.
     *
     *    @return    bool    TRUE is session has been deleted, else FALSE.
     **/

    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );

            return !$this->sessionState;
        }

        return FALSE;
    }
}
