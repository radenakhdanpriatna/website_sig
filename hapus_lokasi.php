<?php
include 'config/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM lokasi WHERE id=$id");
header("Location: index.php");
