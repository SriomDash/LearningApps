<?php
include "db.php";
$conn = connectDB("customer_db");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format!";
        exit;
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        echo "Error: Phone must be exactly 10 digits!";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO customer_data (name, e_mail, ph_number) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    $stmt->execute();
    exit;
}

$result = $conn->query("SELECT * FROM customer_data ORDER BY id DESC");

if (!$result) {
    echo "Error loading data: " . $conn->error;
    exit;
}

echo "<table>
<tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['e_mail']}</td>
        <td>{$row['ph_number']}</td>
        <td>
            <span class='action-btn edit' data-id='{$row['id']}'>Edit</span>
            <span class='action-btn delete' data-id='{$row['id']}'>Delete</span>
        </td>
    </tr>";
}
echo "</table>";
?>
