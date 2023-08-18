<?php
    include 'connect.php';
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }
    $emp=$_SESSION['Emp_id'];
?>


<?php
    include 'connect.php';
    $size = $_GET['size'];
    $gender = $_GET['gender'];
    $add = $_GET['add'];
    $type = $_GET['type'];
    if($add){
        $sql ="insert into `ordertemp$emp` (`type`, `size`, `gender`,`qty`)
        values ('$type', '$size', '$gender', '1')";
        $result =mysqli_query($con, $sql); //insert into ordertemp
        if($result){
            header("location:list-clothe.php?type=$type");
        } else {
            die(mysqli_error($con));
        }
    } else { //delete
        $sql="delete from `ordertemp$emp` where type='$type' and size='$size' and gender='$gender'";
          $result = mysqli_query($con, $sql);
          if($result){
            header("location:list-clothe.php?type=$type");
          } else {
            die(mysqli_error($con));
          }

    }
    

?>