<?php
include 'dbconnect.php';

$id = $_GET['id'];

$sql = "DELETE FROM nodes WHERE NodeID= $id";

if (mysqli_query($conn, $sql)) {
    header("Location: contentsuccess.php");
} else {
    echo "Delete failed: " . mysqli_error($conn);
}
?>