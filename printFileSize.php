<?php
include_once '/model/model.class.php';
include_once '/entity/files.class.php';

$select = "Please select among the field: \n";
$select .= "filename, size, unit (KB, MB or GB), path, actual_size (in bytes), full (full path) or date\n";
print $select;
flush();
$field = trim(fgets(STDIN));

$andWhere = "";

if($field == "size")
{
    $msg = "Please indicate file size: \n";
    print $msg;
    flush();
    $inputValue = trim(fgets(STDIN));
    
    $sizeUnitMsg = "\nPlease indicate KB or MB or GB: \n";
    print $sizeUnitMsg;
    flush();
    $sizeUnit = trim(fgets(STDIN));
    
    $andWhere = " AND `unit` = '$sizeUnit'";
}
else if($field == "unit")
{
    $msg = "\nPlease indicate KB or MB or GB: \n";
    print $msg;
    flush();
    $inputValue = trim(fgets(STDIN));        
}
else if($field == "actual_size")
{
    $msg = "\nPlease indicate file size in bytes: \n";
    print $msg;
    flush();
    $inputValue = trim(fgets(STDIN));        
}
else if($field == "filename" || $field == "path" || $field == "full" || $field == "date")
{
    $msg = "Please supply parameter to search: \n";
    print $msg;
    flush();
    $inputValue = trim(fgets(STDIN));  
}

$model = new Model();
$File  = new Files();
$list  = $model->displayRecord($File, $field, $inputValue, $andWhere);

$new = "";
$separator = "\n";
foreach($list as $k => $v)
{
    $new .= "Filename: " . $v['filename'] . $separator;
    $new .= "Size: "     . $v['size']     . $separator;
    $new .= "Unit: "     . $v['unit']     . $separator;
    $new .= "Path: "     . $v['path']     . $separator;
    $new .= "Actual Size: " . $v['actual_size'] . $separator;
    $new .= "Full Path: " . $v['full']    . $separator;
    $new .= "Date: "     . $v['date']     . $separator;
    $new .= $separator;
}

$saveMessage = "Please enter Filename: \n";
print $saveMessage;
flush();

$confirmation = trim(fgets(STDIN));
$filename     = $confirmation;

$folder = __DIR__ . "\\text_files\\";
if(!(file_exists($folder)))
{
    exec("mkdir $folder");
}                

$filename .= ".txt";

$file = fopen($folder . $filename, "w");

fwrite($file, $new);       
fclose($file);

die;
?>