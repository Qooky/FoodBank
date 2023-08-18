<?php
    include 'connect.php';
    session_start();
    if(!isset($_SESSION['username'])){
        header('location:signin.php');
    }
    $id = $_SESSION['Emp_id'];
    $sql = "Select * from `employee` where Emp_id = '$id'";
    $result = mysqli_query($con, $sql);
    $row=mysqli_fetch_assoc($result);
    if($result){
      $fname = $row['Fname'];
      $lname = $row['Lname'];
      $role = $row['role'];
      $username = $row['username'];
      $semp_id = $row['Semp_id'];
      $tableString = "Role";
      $tableHead = "List of employees";  
      if ($role != "Supervisor"){
        $sql1 = "Select * from `employee` where Emp_id = '$semp_id'";
        $result1 = mysqli_query($con, $sql1);
        $row1=mysqli_fetch_assoc($result1);
        if($result1){
            $Sfname = $row1['Fname'];
            $Slname = $row1['Lname'];
            $Susername = $row1['username'];
        }
        $tableString = "Supervisor username";
        $tableHead = "Supervisor"; 
      }
    }
    if($role == "Supervisor"){
        $x = "super-home.php";
        $s = $role;
    }
    else if ($role == "Front"){
        $x = "front-home.php";
        $s = $role . " Employee";
    }
    else{
        $x = "back-home.php";
        $s = $role . " Employee";
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <div class="d-flex justify-content-between">
        <a href="<?php echo $x;?>" class="btn btn-primary m-2">Home</a>
        <a href="logout.php" class="btn btn-primary m-2">Logout</a>
    </div>
    <h1 class="text-center mt-5">Profile</h1>
    <div class="container mt-5 d-flex flex-column justify-content-start w-50">
        <p><?php echo "Username: " . $username;?></p>
        <p><?php echo "First name: ". $fname?></p>
        <p><?php echo "Last name: " .$lname;?></p>
        <p><?php echo "Role: " . $s;?></p>
        <table class="table table-bordered caption-top">
            <caption><strong><?php echo $tableHead;?></strong></caption>
            <thead>
                <tr>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col"><?php echo $tableString;?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($role == "Supervisor"){
                        $sql = "Select * 
                        from `employee`
                        where role !='Supervisor' and Semp_id = '$id'";
                        $result=mysqli_query($con, $sql);
                        if($result){
                            while($row=mysqli_fetch_assoc($result)){
                                $fname = $row['Fname'];
                                $lname = $row['Lname'];
                                $role = $row['role'];
                                $super = $row['Semp_id'];
                                echo '<tr>
                                <td>'.$fname.'</td>
                                <td>'.$lname.'</td>
                                <td>'.$role.'</td>
                                </tr>
                                ';   
                            }
                        }
                    }
                    else{
                        echo '<tr>
                        <td>'.$Sfname.'</td>
                        <td>'.$Slname.'</td>
                        <td>'.$Susername.'</td>
                        </tr>
                        ';
                    } 
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <a class="btn btn-primary w-50" href="change-password.php" role="button">Change Password</a>
        </div>
    </div>
  </body>
</html>