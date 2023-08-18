<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != "Supervisor"){
        header('location:signin.php');
    }
?>

<?php
    include 'connect.php';
    $invalid = 0;
    $dup = 0;
    $success = 0;
    if(isset($_POST['create'])){
        $role = $_POST['rol'];
        $user = $_POST['username'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $pw = "123"; //password is set to 123 by default
        $id = $_SESSION['Emp_id']; //supervisor is who ever is making the emp
        
        if(empty($role) || empty($user) || empty($fname) || empty($lname)){
            $invalid = 1;
        } else {
            $sql = "Select * from `employee` where username='$user'";
            $result = mysqli_query($con, $sql);
            if($result){ //check if username exists
                $num=mysqli_num_rows($result);
                if($num > 0) {
                    $dup = 1;
                } 
                else if ($role == "Supervisor"){
                  $sql ="insert into `employee` (Fname, Lname, Semp_id, username, password, role)
                    values ('$fname', '$lname', NULL, '$user', '$pw', '$role')";
                    $result =mysqli_query($con, $sql);

                    if($result){
                        $semp_id = mysqli_insert_id($con);
                        $sql1 ="insert into `supervisor` (Semp_id)
                        values ('$semp_id')";
                        $result1 =mysqli_query($con, $sql1);
                        if($result1) {
                          $success = 1;
                        } else {
                          die(mysqli_error($con));
                        }
                    } else {
                        die(mysqli_error($con));
                    }
                }
                else {
                    $sql ="insert into `employee` (Fname, Lname, Semp_id, username, password, role)
                    values ('$fname', '$lname', '$id', '$user', '$pw', '$role')";
                    $result =mysqli_query($con, $sql);

                    if($result){
                        if($role == "Back"){
                          $bemp_id = mysqli_insert_id($con);
                          $sql1 ="insert into `back_employee` (Bemp_id)
                          values ('$bemp_id')";
                          $result1 =mysqli_query($con, $sql1);
                          if($result1) {
                            $success = 1;
                          } else {
                            die(mysqli_error($con));
                          }

                        } else {
                          $femp_id = mysqli_insert_id($con);
                          $sql1 ="insert into `front_employee` (Femp_id)
                          values ('$femp_id')";
                          $result1 =mysqli_query($con, $sql1);
                          if($result1) {
                            $success = 1;
                          } else {
                            die(mysqli_error($con));
                          }

                        }
                        
                    } else {
                        die(mysqli_error($con));
                    }
                }
            }
        }

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
    <title>Add Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <?php
      if($success){
        echo '<div class="alert alert-success" role="alert">
        The user <strong>'.$user.'</strong> was successfully created!.
      </div>';
      }
    ?>
    <?php
      if($invalid){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> Please fill information for all the fields and select the role.
      </div>';
      }
    ?>
    <?php
      if($dup){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> This username is already taken.
      </div>';
      }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
    <div class="d-flex justify-content-between">
      <a href="employees.php" class="btn btn-primary m-2">Back</a>  
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
        <form action="addemp.php" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="Enter username" name="username">

                <label class="form-label mt-4">First name</label>
                <input type="text" class="form-control" placeholder="Enter first name" name="fname">

                <label class="form-label mt-4">Last name</label>
                <input type="text" class="form-control" placeholder="Enter last name" name="lname">

                <div class="form-text mt-4">Password is automatically set to: 123</div>
                <div class="form-text">You will be this employee's supervisor</div>
                <div class="form-text">If you are adding a supervisor, then no one will be your supervisor</div>
                <label class="form-label mt-4">Role</label>

                <select name="rol" class="form-select" multiple aria-label="multiple select example">
                <option selected value="Front">Front employee</option>
                <option value="Back">Back employee</option>
                <option value= "Supervisor">Supervisor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="create">Create</button>
        </form>
    </div>
    
  </body>
</html>