<?php

function backup_mysql_database($options){
    $mtables = array(); 
    $contents = "-- Database: `".$options['db_to_backup']."` --\n";
    $contents .= "SET NAMES utf8mb4;\n";
    $contents .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    $mysqli = new mysqli($options['db_host'], $options['db_uname'], $options['db_password'], $options['db_to_backup']);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
    
    // Set character set to UTF-8
    $mysqli->set_charset("utf8mb4");

    $results = $mysqli->query("SHOW TABLES");
    if (!$results) {
        die('Query Error (' . $mysqli->errno . ') ' . $mysqli->error);
    }
    
    while($row = $results->fetch_array()){
        if (!in_array($row[0], $options['db_exclude_tables'])){
            $mtables[] = $row[0];
        }
    }
    
    foreach($mtables as $table){
        $contents .= "-- Table `".$table."` --\n";
    
        $results = $mysqli->query("SHOW CREATE TABLE ".$table);
        if (!$results) {
            die('Query Error (' . $mysqli->errno . ') ' . $mysqli->error);
        }
        while($row = $results->fetch_array()){
            $contents .= $row[1].";\n\n";
        }
    
        $results = $mysqli->query("SELECT * FROM ".$table);
        if (!$results) {
            die('Query Error (' . $mysqli->errno . ') ' . $mysqli->error);
        }
        $row_count = $results->num_rows;
        $fields = $results->fetch_fields();
        $fields_count = count($fields);
    
        $insert_head = "INSERT INTO `".$table."` (";
        for($i=0; $i < $fields_count; $i++){
            $insert_head  .= "`".$fields[$i]->name."`";
            if($i < $fields_count-1){
                $insert_head  .= ', ';
            }
        }
        $insert_head .=  ")";
        $insert_head .= " VALUES\n";        
    
        if($row_count > 0){
            $r = 0;
            while($row = $results->fetch_array(MYSQLI_NUM)){
                if(($r % 400)  == 0){
                    $contents .= $insert_head;
                }
                $contents .= "(";
                for($i=0; $i < $fields_count; $i++){
                    if ($row[$i] === null) {
                        $contents .= "NULL";
                    } else  {
                        $row_content = str_replace("\n","\\n",$mysqli->real_escape_string($row[$i]));
                        $row_content = mb_convert_encoding($row_content, 'UTF-8', 'UTF-8');
                        switch($fields[$i]->type){
                            case 8: case 3:
                                $contents .=  $row_content;
                                break;
                            default:
                                $contents .= "'". $row_content ."'";
                        }
                    }
                    if($i < $fields_count-1){
                        $contents  .= ', ';
                    }
                }
                if(($r+1) == $row_count || ($r % 400) == 399){
                    $contents .= ");\n\n";
                }else{
                    $contents .= "),\n";
                }
                $r++;
            }
        }
    }
    
    $contents .= "SET FOREIGN_KEY_CHECKS = 1;\n";
    return $contents;
}

$options = array(
    'db_host'=> 'localhost',  // MySQL host
    'db_uname' => 'root',  // User
    'db_password' => '', // Password
    'db_to_backup' => 'ims26', // Database name
    'db_backup_path' => './db_backup', // Where to backup
    'db_exclude_tables' => array() // Tables to exclude
);
$backup_contents = backup_mysql_database($options);

if ($backup_contents) {
    // Clear all output buffering
    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="pms23_' . date("Ymd_His") . '.sql"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($backup_contents));
    header('Content-Type: text/plain; charset=utf-8');
    echo $backup_contents;
    exit;
} else {
    die("Database export failed.");
}
?>
