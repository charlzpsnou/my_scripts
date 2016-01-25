<?php
session_start();
require_once("model.php");
require_once("parser.php");
require_once("formCreator.php");
?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Страничка просмотра статистики</title>

</head>
<body>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" accept-charset="UTF-8" >
<select name="itenerary_type" required>
    <?php
        $form_creator = formCreator::getFormCreator();
        $form_creator->create_itenerary_type_options();
    ?>
</select>
<select name="year" required>
    <?php
    $form_creator=formCreator::getFormCreator();
    $form_creator->create_years_options();
    ?>
</select>
<select name="month" required>
    <?php
    $form_creator=formCreator::getFormCreator();
    $form_creator->create_months_options();
    ?>
</select>
<select name="day" required>
    <?php
    $form_creator=formCreator::getFormCreator();
    $form_creator->create_days_options();
    ?>
</select>
    <input type="submit" name="view_itenerary_list" value="Получить маршрутный лист">
</form>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" accept-charset="UTF-8" >
    <input type="text" name="itenerary_type" value="">
    <input type="submit" name="save_type" value="Сохранить новый маршрут">
</form>

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
    <select name="itenerary_type" required>
        <?php
        $form_creator=formCreator::getFormCreator();
        $form_creator->create_itenerary_type_options();
        ?>
    </select>
    <input type="file" name="myfile">
    <input type="submit" name="doupload" value="Загрузить на сайт файл маршрутного листа">
</form>

<?php if(isset($_REQUEST['save_type'])) {
    try {
        $enter_db = model::getDatabaseInstance();
        $enter_db->setNewIteneraryType($_POST['itenerary_type']);
    }catch(Exception $e){ echo $e; }
} ?>

<?php if($_REQUEST['view_itenerary_list']){
//    $form_creator=formCreator::getFormCreator();
//    $form_creator->save_choise($_REQUEST['year'],$_REQUEST['month'],$_REQUEST['day']);
    $date.=$_REQUEST['year'];
    $date.="-".$_REQUEST['month'];
    $date.="-".$_REQUEST['day'];
    $i_type=$_REQUEST['itenerary_type'];
    $enter_db=model::getDatabaseInstance();
    $container=$result=$enter_db->getRowsFromTableWithParams($i_type, $date);

} ?>

<?php if(isset($_REQUEST['doupload'])){
    if(!copy($_FILES['myfile']['tmp_name'], "./first.csv")){echo "Some troubles, when uploading file";};
    if(!is_readable("./first.csv") ){echo "File is not readable";}
    $itenerary_type=$_REQUEST['itenerary_type'];
    $f = fopen("./first.csv", 'r+');
    $file_parser=new parser($f, $itenerary_type);
    $container=$datas=$file_parser->parseCSV();
    fclose($f);

}?>

<?php if(isset($container)){

if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
    ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^
        PHP_OUTPUT_HANDLER_REMOVABLE);
} else {
    ob_start(null, 0, false);
}

echo "<table border='1px' align=''>";
foreach($container as $row){
    if($row===0){echo "<th>";}
    else {echo "<tr>";}
    foreach($row as $col){
        echo "<td>".$col."</td>";
    }
    echo "<tr>";
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
    echo '<br><a href="temp.html" download="load" target="_top" style="border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #0000FF; /* Цвет текста */"> Сохранить файл на диск </a><br>';
}?>

</body>
</html>



