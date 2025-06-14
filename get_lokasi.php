<?php
include 'config/config.php';
$result = $conn->query("SELECT * FROM lokasi");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
