<?php
require_once("model.php");
require_once("parser.php");
require_once("formCreator.php");
require_once("DateGenerator.php");
?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Страничка просмотра статистики</title>

</head>
<body>
<?php if(isset($_REQUEST['save_type'])) {
    try {
        $enter_db = model::getDatabaseInstance();
        $enter_db->setNewIteneraryType($_POST['itenerary_type']);
    }catch(Exception $e){
        echo $e;
    }
    Header("Location:http://{$_SERVER['SERVER_NAME']}{$_SERVER['SCRIPT_NAME']}");
    exit();
} ?>


<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" accept-charset="UTF-8" >
    <?php
    $form_creator = formCreator::getFormCreator();
    $itenerary_type_options=$form_creator->getIteneraryTypes();
    $date_options=$form_creator->getDateOptions();
    ?>
<select name="itenerary_type" required>
    <?php
    echo "<option disabled selected>Выберите Маршрут</option>";
    foreach($itenerary_type_options as $itenerary_type){
        echo "<option> ".$itenerary_type."</option>";
    }
    ?>
</select>
<select name="year" required>
    <?php
    foreach($date_options['years'] as $year ){
        if ($year==date('Y')){
            echo '<option selected> '.$year."</option>";
        }
        else {
            echo '<option > '.$year."</option>";
        }
    }
    ?>
</select>
<select name="month" required>
    <?php
    foreach($date_options['months'] as $month ){
        if ($month==date('m')){
            echo '<option selected> '.$month."</option>";
        }
        else {
            echo '<option > '.$month."</option>";
        }
    }
    ?>
</select>
<select name="day" required>
    <?php
    foreach($date_options['days'] as $day){
        if ($day==date('d')){
            echo '<option selected> '.$day."</option>";
        }
        else {
            echo '<option > '.$day."</option>";
        }
    }
    ?>
</select>
    <input type="submit" name="view_itenerary_list" value="Получить список остановок на маршруте">
</form>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" accept-charset="UTF-8" >
    <input type="text" name="itenerary_type" value="">
    <input type="submit" name="save_type" value="Сохранить новый маршрут">
</form>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
    <select name="itenerary_type" required>
        <?php
        echo "<option disabled selected>Выберите Маршрут</option>";
        foreach($itenerary_type_options as $itenerary_type){
            echo "<option> ".$itenerary_type."</option>";
        }
        ?>
    </select>
    <input type="file" name="myfile">
    <input type="submit" name="doupload" value="Загрузить на сайт файл списка остановок на маршруте">
</form>

<?php if($_REQUEST['view_itenerary_list']){
//    $form_creator=formCreator::getFormCreator();
//    $form_creator->save_choise($_REQUEST['year'],$_REQUEST['month'],$_REQUEST['day']);
    $date.=$_REQUEST['year'];
    $date.="-".$_REQUEST['month'];
    $date.="-".$_REQUEST['day'];
    $i_type=$_REQUEST['itenerary_type'];
    $enter_db=model::getDatabaseInstance();
    $datas_container=$result=$enter_db->getRowsFromTableWithParams($i_type, $date);
} ?>

<?php if(isset($_REQUEST['doupload'])){
    if(!copy($_FILES['myfile']['tmp_name'], "./tempfile.csv")){
        echo "Какие-то проблемы с загрузкой файла, возможно, файл не выбран";
    }
    elseif(!is_readable("./tempfile.csv") ){
        echo "Файл невозможно прочитать, какие-то проблемы с правами на чтение";
    }
    else {
        $itenerary_type = $_REQUEST['itenerary_type'];
        $f = fopen("./tempfile.csv", 'r+');
        if ($f === false) {
            echo "Не удалось открыть файл";
        }
        else {
            $file_parser = new parser($f, $itenerary_type);
            $datas_container = $file_parser->parseCSV();
            fclose($f);
        }
    }
}?>


<table>
    <tr><td>


<?php if(isset($datas_container)){

    $date_generator=DateGenerator::createDateGenerator();
    $intervals_column=$date_generator->parseColumn($datas_container, 2);
    $new_intervals_column=$date_generator->generateIntervals($intervals_column, 20, 40);
//    var_dump($new_intervals_column);
    $generated_data_container=$date_generator->insertColumnToArray($datas_container, $new_intervals_column, 2);
    echo "<table border='1px' align=''>";
    foreach($generated_data_container as $row_number=>$row){
        echo "<tr>";
        foreach($row as $col){
            echo "<td>".$col."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

echo "</td>";

if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
    ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^
        PHP_OUTPUT_HANDLER_REMOVABLE);
} else {
    ob_start(null, 0, false);
}
    echo "<td>";
    echo "<table border='1px' align=''>";
    foreach($datas_container as $row_number=>$row){
        echo "<tr>";
        foreach($row as $col){
            echo "<td>".$col."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $output = ob_get_contents();
    ob_end_clean();
    if(!$file = fopen("temp.html","wb")) {echo "Не удалось сохранить файл";}
    else {
        $downloader=true;
        fwrite($file, iconv('UTF-8', 'Windows-1251', $output));
        fclose($file);
    }
    echo "</td></tr>";

    echo '<tr><td><br><a href="temp.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #0000FF; /* Цвет текста */"> Сохранить файл на диск </a><br></td></tr>';
}?>


</table>
</body>
</html>