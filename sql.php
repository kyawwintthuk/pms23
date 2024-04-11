<?php
// include('db/config.php');

// $tbl_name = $_GET['tbl'];


// $sql1 = "
// SELECT * FROM `consume`;
// ";

// $output = "";
// $output.= "INSERT INTO `consume`(`name`, `shopping_date`, `consume_date`, `eaten_date`, `expire_date`, `price`, `property_id`, `sys_date`) VALUES "; 
// $result = $conn->query($sql1);
// $num_rows = mysqli_num_rows($result);
// $row_num = 0;
// foreach($result as $row){
//     $row_num++;

//     $output .= '(\''.$row['name'].'\','.
//     '\''.$row['shopping_date'].'\','.
//     '\''.$row['consume_date'].'\','.
//     '\''.$row['eaten_date'].'\','.
//     '\''.$row['expire_date'].'\','.
//     '\''.$row['price'].'\','.
//     '\''.$row['property_id'].'\','.
//     '\''.$row['sys_date'].'\')'                        
//     ;
//     if($row_num != $num_rows) {
//         $output.=',';
//     }
// }

// $output.=';';

// echo $output;   

// Connect to database
// $conn = new PDO("mysql:host=localhost;dbname=pms", "root", "");

// // Get all table names in the database
// $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

// // Loop over each table
// foreach ($tables as $table) {
//     // Select all rows from the table
//     $stmt = $conn->prepare("SELECT * FROM $table");
//     $stmt->execute();
//     $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Generate insert query for the table
//     $output = "INSERT INTO `$table` (";
//     $columns = array_keys($rows[0]);
//     $output .= "`" . implode("`, `", $columns) . "`";
//     $output .= ") VALUES ";
//     $values = array();
//     foreach ($rows as $row) {
//         $values[] = "('" . implode("', '", $row) . "')";
//     }
//     $output .= implode(", ", $values);
//     $output .= ";";
//     $output .= "<br><br><br>";

//     // Output insert query
//     echo $output;
// }


// Connect to database
$conn = new PDO("mysql:host=localhost;dbname=pms", "root", "");

// Get all table names in the database
$tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

// Loop over each table
foreach ($tables as $table) {
    // Select all rows from the table
    $stmt = $conn->prepare("SELECT * FROM $table");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the table columns, excluding the auto-increment 'id' column
    $columns = array_filter(array_keys($rows[0]), function ($column) {
        return $column != 'id';
    });

    // Generate insert query for the table
    $output = "INSERT INTO `$table` (";
    $output .= "`" . implode("`, `", $columns) . "`";
    $output .= ") VALUES ";
    $values = array();
    foreach ($rows as $row) {
        // Get the row values, excluding the 'id' column
        $row_values = array_map(function ($key) use ($row) {
            return $row[$key];
        }, array_filter(array_keys($row), function ($key) {
            return $key != 'id';
        }));

        $values[] = "('" . implode("', '", $row_values) . "')";
    }
    $output .= implode(", ", $values);
    $output .= ";";
    $output .= "<br><br><br>";
    // Output insert query
    echo $output;
}
