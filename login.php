	<?php
    $con = mysqli_connect(""); // RDS 주소, 계정, 비밀번호
    mysqli_query($con,'SET NAMES utf8');
 
    $userID = $_POST["userID"];
    $userPassword = $_POST["userPassword"];
 
    $statement = mysqli_prepare($con, "SELECT * FROM signup_student WHERE userID = ? AND userPassword = ?");
    mysqli_stmt_bind_param($statement, "ss", $userID, $userPassword);
    mysqli_stmt_execute($statement);
 
 
    mysqli_stmt_store_result($statement);
    mysqli_stmt_bind_result($statement, $userName, $userNick, $userID, $userPassword);
 
    $response = array();
    $response["success"] = false;
 
    while(mysqli_stmt_fetch($statement)) {
        $response["success"] = true;
        $response["userName"] = $userName;
        $response["userNick"] = $userNick;
        $response["userID"] = $userID;
        $response["userPassword"] = $userPassword;
    }
 
    echo json_encode($response);
 
 
 
?>
