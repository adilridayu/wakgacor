<?php
require "c:/xampp/htdocs/WakGacor2/includes/db.php";

$username = "admin1";
$password = "admin123";
$new_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password = :password WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":password", $new_hash);
$stmt->bindParam(":username", $username);

if ($stmt->execute()) {
    echo "Password for admin1 updated successfully to modern hash.\n";
} else {
    echo "Failed to update password.\n";
}
?>
