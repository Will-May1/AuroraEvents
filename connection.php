<?php
$servername="sql202.infinityfree.com";
$username="if0_43187131";
$password="hHimykmJ2nY";
$dbname="if0_43187131_AuroraEventsMain";
try{
    $conn= new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SESSION['showconnect'] != 0) {
        echo("Connected successfully<br>");
    }   
}
catch(PDOException $e){
    echo("Connection failed: " . $e->getMessage());
}
?>