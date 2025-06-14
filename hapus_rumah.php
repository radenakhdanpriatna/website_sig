<?php
include 'config/config.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "DELETE FROM data_rumah WHERE id = $id";
  $result = mysqli_query($conn, $query);

  if ($result) {
    header("Location: manage_rumah.php?hapus=success");
  } else {
    header("Location: manage_rumah.php?hapus=fail");
  }
} else {
  header("Location: manage_rumah.php");
}
exit();
