<?php
    $con = mysqli_connect(); //RDS 주소, 계정, 비밀번호
    mysqli_query($con,'SET NAMES utf8');

    $userName = $_POST["userName"]; //안드로이드 앱에서 불러온 값
    $userNick = $_POST["userNick"];
    $userID = $_POST["userID"];
    $userPassword = $_POST["userPassword"];

 
    $statement = mysqli_prepare($con, "INSERT INTO signup_student VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($statement, "ssss", $userName, $userNick, $userID, $userPassword);
    mysqli_stmt_execute($statement);
 
 
    $response = array();
    $response["success"] = true;
 
 
    echo json_encode($response);
 
?>
