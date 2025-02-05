<?php
session_start();

ini_set("display_errors", 1);
error_reporting(E_ALL);

$host = "localhost";
$db = "login";
$user = "root";
$pass = "";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password === $confirm_password){
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0){
            $message = "Le nom d'utilisateur est déjà pris.";
        } else{
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $password_hash]);
            $message = "Inscription réussie.";
        }
    } else{
        $message = "Les mots de passe ne correspondent pas.";
    }
}

if (!empty($message)){
    echo "<script>alert('" . addslashes($message) . "');</script>";
}
?>
