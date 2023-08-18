<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }

    include 'connect.php';
    if(isset($_GET['o'])){
        $order = $_GET['o'];
        $fam_id;
        $sql ="insert into `family` (`Fam_id`)
        values (NULL)";
        $result =mysqli_query($con, $sql); //insert into family (auto generate id)
        if($result){
            $fam_id = mysqli_insert_id($con); //get the id of the family just inserted
        } else {
            die(mysqli_error($con));
        }
        if($order == "food"){
            header('location:start-forder.php?fam='.$fam_id.'');
        } else { //order is clothe
            header('location:start-corder.php?fam='.$fam_id.'');
        }

    }
        


?>

