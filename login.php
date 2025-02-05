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

    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])){
        $_SESSION["username"] = $username;
        $message = "Connecté.";
    } else{
        $message = "Identifiant ou mot de passe incorrect.";
    }
}

if (!empty($message)){
    echo "<script>alert('" . addslashes($message) . "');</script>";
}
?>
