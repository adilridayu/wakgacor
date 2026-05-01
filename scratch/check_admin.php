<?php
require "c:/xampp/htdocs/WakGacor2/includes/db.php";

$username = "admin1";
$password = "admin123";

$sql = "SELECT * FROM admins WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "Admin found.\n";
    if (password_verify($password, $admin['password'])) {
        echo "Password matches!\n";
    } else {
        echo "Password does NOT match.\n";
        echo "Stored hash: " . $admin['password'] . "\n";
        echo "New hash for admin123: " . password_hash($password, PASSWORD_DEFAULT) . "\n";
    }
} else {
    echo "Admin NOT found.\n";
    // Create it if requested or just report
}
?>
