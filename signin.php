<?php
  $invalid=0;

  if ($_SERVER['REQUEST_METHOD']=='POST'){
    include 'connect.php';
    $username=$_POST['username'];
    $password=$_POST['password'];

    $sql = "Select * from `employee` where username='$username' and password='$password'";

    $result = mysqli_query($con, $sql);
    if($result){
      $num=mysqli_num_rows($result);
      if($num > 0){
        $login = 1;
        session_start();
        $_SESSION['username'] = $username; //set username
        //for now only direct to super-home.php
        $row=mysqli_fetch_assoc($result);
        $e_id=$row['Emp_id'];
        $role=$row['role'];
        $_SESSION['Emp_id'] = $e_id; //set empid
        $_SESSION['role'] = $role; //set role

        if($role == "Supervisor"){
          header('location:super-home.php');
        } else if ($role == "Back"){
          header('location:back-home.php');
        } else {
          header('location:front-home.php');
        }
        
      } else {
        $invalid=1;

      }
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <?php
      if($invalid){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> Invalid credentials.
      </div>';
      }
    ?>
    <h1 class="text-center mt-5">Calgary Food Bank</h1> 
    <div class="container mt-5">
        <form action="signin.php" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="Enter your username" name="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Enter your password" name="password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Log in</button>
        </form>
    </div>
    
  </body>
</html>