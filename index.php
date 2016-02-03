<?php
//require_once("model.php");
require_once("parser.php");
//require_once("formCreator.php");
require_once("DateGenerator.php");
require_once("Session.php");
require_once("StopTimeGenerator.php");
?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Страничка просмотра статистики</title>

</head>
<body>

<?php //if(isset($_REQUEST['save_type'])) {
//    try {
//        if($_POST['itenerary_type']!=="") {
//            $enter_db = model::getDatabaseInstance();
//            $enter_db->setNewIteneraryType($_POST['itenerary_type']);
//        }
//    }catch(Exception $e){
//        echo $e;
//    }
//    Header("Location:http://{$_SERVER['SERVER_NAME']}{$_SERVER['SCRIPT_NAME']}");
//    exit();
//} ?>



<?php //if($_REQUEST['view_itenerary_list']){
//
//    $date.=$_REQUEST['year'];
//    $date.="-".$_REQUEST['month'];
//    $date.="-".$_REQUEST['day'];
//    $i_type=$_REQUEST['itenerary_type'];
//    $enter_db=model::getDatabaseInstance();
//    $data_container=$result=$enter_db->getRowsFromTableWithParams($i_type, $date);
//    $session=Session::getInstance();
//    $session->data_container=$data_container;
//} ?>



<!--<form action="--><?php //$_SERVER['SCRIPT_NAME']?><!--" method="POST" accept-charset="UTF-8" name="view_itenerary_list_from" >-->
<!--    --><?php
//    $form_creator = formCreator::getFormCreator();
//    $itenerary_type_options=$form_creator->getIteneraryTypes();
//    $date_options=$form_creator->getDateOptions();
//    ?>
<!--<select name="itenerary_type" required>-->
<!--    --><?php
//    echo "<option disabled selected>Выберите Маршрут</option>";
//    foreach($itenerary_type_options as $itenerary_type){
//        echo "<option> ".$itenerary_type."</option>";
//    }
//    ?>
<!--</select>-->
<!--<select name="year" required>-->
<!--    --><?php
//    foreach($date_options['years'] as $year ){
//        if ($year==date('Y')){
//            echo '<option selected> '.$year."</option>";
//        }
//        else {
//            echo '<option > '.$year."</option>";
//        }
//    }
//    ?>
<!--</select>-->
<!--<select name="month" required>-->
<!--    --><?php
//    foreach($date_options['months'] as $month ){
//        if ($month==date('m')){
//            echo '<option selected> '.$month."</option>";
//        }
//        else {
//            echo '<option > '.$month."</option>";
//        }
//    }
//    ?>
<!--</select>-->
<!--<select name="day" required>-->
<!--    --><?php
//    foreach($date_options['days'] as $day){
//        if ($day==date('d')){
//            echo '<option selected> '.$day."</option>";
//        }
//        else {
//            echo '<option > '.$day."</option>";
//        }
//    }
//    ?>
<!--</select>-->
<!--    <input type="submit" name="view_itenerary_list" value="Получить список остановок на маршруте">-->
<!--</form>-->
<!---->
<!--<form action="--><?php //$_SERVER['SCRIPT_NAME']?><!--" method="POST" accept-charset="UTF-8" name="save_type_form">-->
<!--    <input type="text" name="itenerary_type" value="">-->
<!--    <input type="submit" name="save_type" value="Сохранить новый маршрут">-->
<!--</form>-->

<?php if(isset($_REQUEST['doupload'])){
    if(!copy($_FILES['myfile']['tmp_name'], "./tempfile.csv")){
        echo "Какие-то проблемы с загрузкой файла, возможно, файл не выбран";
    }
    elseif(!is_readable("./tempfile.csv") ){
        echo "Файл невозможно прочитать, какие-то проблемы с правами на чтение";
    }
    else {
//        $itenerary_type = $_REQUEST['itenerary_type'];
        $file_descriptor = fopen("./tempfile.csv", 'r+');
        if ($file_descriptor === false) {
            echo "Не удалось открыть файл";
        }
        $data_container=array();
        for ($i = 0; $data = fgetcsv($file_descriptor, 1000, ','); $i++) {
            $num = count($data);
            for ($k = 0; $k < $num; $k++) {
                $data_container[$i][$k]=$data["$k"];
            }
        }


//        else {
//            $file_parser = new parser($f, $itenerary_type);
//            $data_container = $file_parser->parseCSV();
//            fclose($f);
//        }
    }
}?>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data" name="doupload_form">
<!--    <select name="itenerary_type" required>-->
<!--        --><?php
//        echo "<option disabled selected>Выберите Маршрут</option>";
//        foreach($itenerary_type_options as $itenerary_type){
//            echo "<option> ".$itenerary_type."</option>";
//        }
//        ?>
<!--    </select>-->
    <input type="file" name="myfile">
    <input type="submit" name="doupload" value="Загрузить на сайт файл списка остановок на маршруте">
</form>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" accept-charset="UTF-8" name="generate">
    <input type="submit" name="generate" value="Сгенерировать маршрутный лист на основании шаблона">
</form>



<table>

    <tr>
        <td>
            <?php if(isset($_REQUEST['view_itenerary_list']) || isset($_REQUEST['doupload']) || isset($_POST['generate'])){ ?>
                <a href="temp.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #0000FF; /* Цвет текста */">
                    Сохранить файл на диск </a>

            <?php }?>
        </td>

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
            <?php if(isset($_REQUEST['view_itenerary_list']) || isset($_REQUEST['doupload']) || isset($_POST['generate'])) {
                $session = Session::getInstance();
                if (isset($session->data_container)) {
                    $data_container = $session->data_container;

//                    $variable=new StopTimeGenerator($data_container);
//
//                    var_dump($variable->start_time);
//                    var_dump($variable->start_date);
//                    var_dump($variable->array_size);
//
//                    $column=$variable->getColumn($data_container, 6);
//                    $distances=$variable->getDistances($column);
//                    $relative_distances=$variable->getRelativeDistances($distances);
//
//                    $velocity_array=$variable->getVelocity($relative_distances);
//
//                    $time_array=$variable->getTimeOfStoppage($data_container);
//                    $date_array=$variable->convertDateToFormat($time_array);
//
//                    echo "<tr><td>";
//                    var_dump($distances);
//                    echo "</td><td>";
//                    var_dump($relative_distances);
//                    echo "</td><td>";
//                    var_dump($date_array);
//                    echo "</td><td>";
//                    var_dump($time_array);
//
//                    $intervals_column=$variable->getColumn($data_container, 2);
//                    $intervals_array=$variable->getIntervals($intervals_column);
//                    echo "</td><td>";
//                    var_dump($intervals_array);
//                    echo "</td></tr>";
//
//                    $variable->getDateOfStoppage();


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

                    echo "<table border='1px' align=''>";
                    foreach ($data_container as $row_number => $row) {
                        echo "<tr>";
                        foreach ($row as $col) {
                            echo "<td><div style='font-size: small'>".$col."</div></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";

                    $output = ob_get_contents();
                    ob_end_clean();
                    if (!$file = fopen("temp.html", "wb")) {
                        echo "Не удалось сохранить файл";
                    } else {
                        $downloader = true;
                        fwrite($file, iconv('UTF-8', 'Windows-1251', $output));
                        fclose($file);
                    }
                }
            }?>
        </td>

        <td>
            <?php if(isset($_POST['generate'])) {
                $session = Session::getInstance();
                if (isset($session->data_container)) {
                    $data_container = $session->data_container;
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
                    $date_generator = DateGenerator::createDateGenerator();
                    $intervals_column = $date_generator->parseColumn($data_container, 2);
                    $new_intervals_column = $date_generator->generateIntervals($intervals_column, 20, 40);
                    $generated_data_container = $date_generator->insertColumnToArray(
                        $data_container,
                        $new_intervals_column,
                        2
                    );
                    $variable=new StopTimeGenerator($generated_data_container);
                    $converted_date=$variable->getTimeOfStoppage($generated_data_container);

                    $generated_data_container= $date_generator->insertColumnToArray(
                        $generated_data_container,
                        $converted_date['begin'],
                        0
                    );
                    $generated_data_container= $date_generator->insertColumnToArray(
                        $generated_data_container,
                        $converted_date['end'],
                        1
                    );

                    echo "<table border='1px' align='' >";
                    foreach ($generated_data_container as $row_number => $row) {
                        echo "<tr>";
                        foreach ($row as $col) {
                            echo "<td><div style='font-size: small'>".$col."</div></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    $output = ob_get_contents();
                    ob_end_clean();
                    if (!$file = fopen("temp_generated.html", "wb")) {
                        echo "Не удалось сохранить файл";
                    } else {
                        $downloader = true;
                        fwrite($file, iconv('UTF-8', 'Windows-1251', $output));
                        fclose($file);
                    }
                }
            }?>
        </td>
    </tr>

</table>

</body>
</html>