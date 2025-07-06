<?php
include "db.php";
$conn = connectDB("customer_db");

$id = $_POST["id"];
$name = $_POST["name"] ?? null;
$email = $_POST["email"] ?? null;
$phone = $_POST["phone"] ?? null;

if ($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format!";
        exit;
    }
    $stmt = $conn->prepare("UPDATE customer_data SET e_mail=? WHERE id=?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    echo "Email updated.";
}

if ($phone) {
    if (!preg_match("/^\d{10}$/", $phone)) {
        echo "Error: Phone must be 10 digits!";
        exit;
    }
    $stmt = $conn->prepare("UPDATE customer_data SET ph_number=? WHERE id=?");
    $stmt->bind_param("si", $phone, $id);
    $stmt->execute();
    echo "Phone updated.";
}

if ($name) {
    $stmt = $conn->prepare("UPDATE customer_data SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    echo "Name updated.";
}
?>
