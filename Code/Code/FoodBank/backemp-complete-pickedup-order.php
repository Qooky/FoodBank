<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Front"){
        header('location:signin.php');
    }
    include 'connect.php';
    $user = $_SESSION['Emp_id'];

    if(isset($_GET['ordNo'])){
        $ordNo = $_GET['ordNo']; //get the id of the employee to edit
        $sql1 = "update `order` set picked_up = 1 where Order_no = $ordNo";
        $result1=mysqli_query($con, $sql1);
        if($result1){
            header('location:pickup-ready-orders.php');
        } else{
            die(mysqli_error($con));
        }
    } else {
        die(mysqli_error($con));
    }
?>
