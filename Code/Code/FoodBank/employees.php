<?php
    include 'connect.php';
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != "Supervisor"){
        header('location:signin.php');
    }
    include 'connect.php';
    $id = $_SESSION['Emp_id'];
    $sql = "Select * from `employee` where Emp_id = '$id'";
    $result = mysqli_query($con, $sql);
    $row=mysqli_fetch_assoc($result);
    if($result){
      $fname = $row['Fname'];
      $lname = $row['Lname'];
      $role = $row['role'];
    }
    else{
      die(mysqli_error($con));
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
    <div class="d-flex justify-content-between">
      <a href="super-home.php" class="btn btn-primary m-2">Back</a>  
        <div class="dropdown m-2">
          <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo $fname . " " . $lname;?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><span class="dropdown-item-text"><strong><?php echo $role;?></strong></span></li>
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" style = "color: red;" href="logout.php">Logout</a></li>
          </ul>
        </div>  
    </div>
    <h1 class="text-center mt-5">Calgary Food Bank</h1> 
    <div class="container mt-5">
        <div class="d-flex justify-content-end">
            <a href="addemp.php" class="btn btn-primary">Add new employee</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Username</th>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col">Role</th>
                <th scope="col">Supervisor username</th>
                <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    //$user = $_SESSION['Emp_id'];
                    $sql = "Select * 
                    from `employee`
                    where role !='Supervisor'";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $user = $row['username'];
                            $fname = $row['Fname'];
                            $lname = $row['Lname'];
                            $role = $row['role'];
                            $super = $row['Semp_id'];
                            $id = $row['Emp_id'];
                            
                            $sql1 = "Select * 
                            from `employee`
                            where Emp_id='$super'";
                            $result1=mysqli_query($con, $sql1);
                            if(!$result1){
                                die(mysqli_error($con));
                            } else {
                                $row1=mysqli_fetch_assoc($result1);
                                $superuser = $row1['username'];
                                echo '<tr>
                                <th scope="row">'.$user.'</th>
                                <td>'.$fname.'</td>
                                <td>'.$lname.'</td>
                                <td>'.$role.'</td>
                                <td>'.$superuser.'</td>
                                <td>
                                <a href="change-role.php?emp='.$id.'" class="btn btn-secondary">role</a>
                                <a href="change-super.php?emp='.$id.'" class="btn btn-secondary">supervisor</a>
                                </td>
                            </tr>
                            ';
                            }

                
                            
                        }
                    }
                ?>
            </tbody>
        </table>
        
    </div>
  </body>
</html>