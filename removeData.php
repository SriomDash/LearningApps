<?php
include "db.php";
$conn = connectDB("customer_db");

$id = $_POST["id"];
$stmt = $conn->prepare("DELETE FROM customer_data WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
?>
