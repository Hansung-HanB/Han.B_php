<?php
    $conn = mysqli_connect("hanb.cszctj008aun.ap-northeast-2.rds.amazonaws.com", "HanB", "gkstjdqlryrhk", "HanB");

    mysqli_query($conn, "set names utf8");

    $userID = $_GET['userID'];

    $sql= "SELECT userName, userMajor, userPoint FROM signup_student WHERE userID = '$userID'";

    $result=mysqli_query($conn, $sql);
    $number_of_rows = mysqli_num_rows($result);

    $response = array();

    if($number_of_rows > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $response[] = $row;
        }
    }

    echo json_encode(array("student"=>$response));

    mysqli_close($conn);
?>