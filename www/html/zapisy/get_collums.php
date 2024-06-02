<?php
require '../../prolog.php';
require INC . '../../include/database.php';

$table = $_GET['table'];

function getTableColumns($table, $conn)
{
    $result = $conn->query("DESCRIBE $table");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

$columns = getTableColumns($table, $conn);
echo json_encode($columns);

$conn->close();
