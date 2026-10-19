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

//Hashes password for protection
$hashedpassword=password_hash($_POST["Password"],PASSWORD_DEFAULT);
echo($hashedpassword."<br>");

if ($_POST['adminCode'] == '17385699'){
    try{
        $stmt=$conn->prepare("INSERT INTO tblAccounts 
        (accountID,Username,passHash)
        VALUES
        (NULL,:Username,:Password)
        ");

        $stmt->bindParam(":Password", $hashedpassword);
        $stmt->bindParam(":Username", $_POST["Username"]);
        $stmt->execute();
    }
    catch(PDOException $e)
    {
        echo("error: " . $e->getMessage());
    }
}
header("location: login");
?>
