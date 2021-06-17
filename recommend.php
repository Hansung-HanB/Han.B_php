<?php
    header("Content-Type:text/html; charset=UTF-8");
 
    $conn= mysqli_connect(""); // RDS 주소, 계정, 
 
    mysqli_query($conn, "set names utf8");
 
    $sql= "SELECT * FROM recommend3 HAVING ratingbar>0 ORDER BY program";
    
    $result=mysqli_query($conn, $sql);
 
    $rowCnt= mysqli_num_rows($result);
 
    $arr= array(); //빈 배열 생성

    $cal_array= array();

    $userID_pair=array();


    for($i=0;$i<$rowCnt;$i++){
        $row= mysqli_fetch_array($result, MYSQLI_ASSOC);
        //각 각의 row를 $arr에 추가
        $arr[$i]= $row;
        $jsonData[$i]=json_encode($arr[$i]); //json배열로 만들어짐.
        $cal_array[$i] = $arr[$i];
        //printf("%d ",$i);
        //print_r($arr[$i]);
               // print_r($cal_array[$i]);
        //printf("<br>");
    }

    /*for($i=0;$i<$rowCnt;$i++){
        array_push($userID_pair,$cal_array[$i]["userID"]);
    }
        print_r($userID_pair);
                printf("<br>");
        $userID_pair=array_unique($userID_pair);
        print_r($userID_pair);
                printf("<br>");*/

    //printf("<br>");

    //printf("Json 형식 <br>");

    //배열을 json으로 변환하는 함수가 있음.
    
    for($i=0;$i<$rowCnt;$i++){
    //echo "$arr[$i]";
        //printf(" %d   %s   %s <br>",$i,$arr[$i],$jsonData[$i]);
        //printf("%s <br>",$arr["program"]);
    }
     //printf("<br><br> 배열 비교 <br>");


     $inner_product=0;
     $left_user_rating=0;
     $right_user_rating=0;
     $calc_ID_row=array();

    //사용자 i와 사용자 j 의 null이 아닌 rating 값이 존재하고, 같은 program을 사용한 사람의 rating 값 찾아서 곱하고 더함
    for($i=0;$i<$rowCnt;$i++){
            for($j=0;$j<$rowCnt-$i;$j++){
                if(($cal_array[$i]["userID"]!=$cal_array[$i+$j]["userID"])AND($cal_array[$i]["program"]==$cal_array[$i+$j]["program"])){

                    //벡터 내적 연산 관련 (분자)
                    //print_r($cal_array[$i]);
                    //printf("<br>");
                    //print_r($cal_array[$i+$j]);
                    //printf("<br>");
                    $cos=($cal_array[$i]["ratingbar"])*($cal_array[$i+$j]["ratingbar"]);//벡터 내적 연산
                    $inner_product=$inner_product+$cos;//분자 
                    //printf("%f",$cos);
                    //printf("<br>");
                    //printf("<br>");

                    //벡터 길이 연산 관련 (분모)
                    $left_user_rating = $left_user_rating + ($cal_array[$i]["ratingbar"])**2;
                    $right_user_rating = $right_user_rating + ($cal_array[$i+$j]["ratingbar"])**2;
                    
                    //$left_user_rating = ($cal_array[$i]["ratingbar"])**2;
                    //$right_user_rating = ($cal_array[$i+$j]["ratingbar"])**2;
                    $userID_I=$cal_array[$i]["userID"];
                    $userID_J=$cal_array[$i+$j]["userID"];
                    //$calc_ID=array("userID_origin" => $userID_I, "userID_predic" => $userID_J, "left_user_rating" => $left_user_rating, "right_user_rating" => $right_user_rating );
                    //array_push($calc_ID_row,$calc_ID);

                   break;
                }
                else
                    continue;
        }
    }


    if($userID_I>$userID_J){
            $temp=$userID_I;
            $userID_I=$userID_J;
            $userID_J=$temp;
        }
        

    //분모 계산 준비
   // for($i=0;$i<10;$i++){
    //print_r($calc_ID_row[$i]["left_user_rating"]);
    //    printf(" ");
    //print_r($calc_ID_row[$i]["right_user_rating"]);
    //printf("<br>");}



    $left_user_rating=sqrt($left_user_rating);
    $right_user_rating=sqrt($right_user_rating);
    //printf("<br> 분자 :  %f <br>",$inner_product);
    //printf("<br> 분모 USER I :  %f <br>",$left_user_rating);
    //printf("<br> 분모 USER J :  %f <br>",$right_user_rating);

    $cos_simul=$inner_product/($left_user_rating*$right_user_rating);
    //printf("<br> 코사인 유사도 :  %f <br><br>",$cos_simul);

    //printf($userID_I);
                    //printf("<br>");

    //printf($userID_J);
                    //printf("<br>");


 //printf("<br>");


    //recommend3에 추천 데이터 넣기

    $sql2= "SELECT * FROM recommend3 HAVING ratingbar is null ORDER BY program";
    
    $result2=mysqli_query($conn, $sql2);
 
    $rowCnt2= mysqli_num_rows($result2);
 
    $arr2= array(); //빈 배열 생성

    $user_array= array();

    //$userID_I_count=array_count_values($userID_I);
    //$userID_J_count=array_count_values($userID_J);



    for($i=0;$i<$rowCnt2;$i++){
        $row2= mysqli_fetch_array($result2, MYSQLI_ASSOC);
        //각 각의 row를 $arr에 추가
        $arr2[$i]= $row2;
        //print_r($arr2[$i]);
        $user_array[$i] = $arr2[$i];
        $jsonData2[$i]=json_encode($arr[$i]); //json배열로 만들어짐.
        //printf("%d ",$i);
        //print_r($user_array[$i]["program"]);
        ///printf(" ");
        //print_r($user_array[$i]["userID"]);
        //print_r($cal_array[$i]["userID"]);
        //printf("<br>");
    }

    $predic_array_row = array();
    $predic_array = array();


    
    //user_array 점수가 없는 배열
    //cal_array 점수가 있는 배열
    for($i=0;$i<$rowCnt2;$i++){

        for($k=0;$k<$rowCnt;$k++){

            if(($user_array[$i]["userID"]==$userID_I)AND($cal_array[$k]["userID"]==$userID_J)){
            //if(($user_array[$i]["userID"]==$userID_I)AND($cal_array[$k]["userID"]==$userID_J)AND($user_array[$i]["program"])==($cal_array[$k]["program"])AND($user_array[$i]["ratingbar"]=="")AND($cal_array[$k]["ratingbar"]!=null)){
                     
                        if(($user_array[$i]["program"])==($cal_array[$k]["program"])){

                            if(($user_array[$i]["ratingbar"]==null)AND($cal_array[$k]["ratingbar"]!=null)){
                            
                            $rating_predic=$cal_array[$k]["ratingbar"]*$cos_simul;
                            //printf("<br>");
                            //printf("사용자2 평점 : %f",$cal_array[$k]["ratingbar"]);
                            //print_r($cal_array[$k]["ratingbar"]);
                            //print_r($user_array[$i]["program"]);
                            //printf("<br>");
                            //printf("사용자1 평점 예측 값 : %f",$rating_predic);
                            //array_push($predic_array,$user_array[$i]["program"],$rating_predic);
                            //array_push($predic_array_row,$predic_array);
                            $rating_predic=(string)$rating_predic;
                            $predic_array = array("program" => $user_array[$i]["program"], "ratingbar" => $rating_predic);
                            array_push($predic_array_row, $predic_array);

                            //printf("<br>");
                            //print_r($predic_array);
                            //printf("<br>");


                        }elseif (($user_array[$i]["ratingbar"]!=null)&&($cal_array[$k]["ratingbar"]!=null)) {
                            break;
                        }
                        continue;


                    }
                    else
                        continue;
                    //break;
            }
            else
              continue;
                //break;
        }
    }

   //print_r($user_array[$a]);
                        //print_r($user_ID_I);
                        //print_r($cal_array[$k]["program"]);


            $jsonData3=json_encode($predic_array_row); //json배열로 만들어짐.
            echo $jsonData3;

    mysqli_close($conn);
 
?>
