<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('location:signin.php');
    }
?>

<?php
    include 'connect.php';
    $invalid = 0;
    $dup = 0;
    $success = 0;
    $retryError = 0;
    $oldError = 0;
    if(isset($_POST['change'])){
        $old_pw_form = $_POST['old_pw_form'];
        $new_pw = $_POST['new_pw'];
        $new_pw_retry = $_POST['new_pw_retry'];
        $id = $_SESSION['Emp_id'];
        $sql = "Select * from `employee` where Emp_id = '$id'";
        $result = mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
        $old_pw;
        if($result){
          $old_pw = $row['password'];
        }
        else {
            die(mysqli_error($con));
        }
        if(!$result || (empty($new_pw) && empty($new_pw_retry))){
            $invalid = 1;
        }
        else if ($new_pw != $new_pw_retry){
            $retryError = 1;
        }
        else if ($old_pw != $old_pw_form){
            $oldError = 1;
        }
        else {
            if ($new_pw != $old_pw && $new_pw_retry == $new_pw){
                $sql1 = "update `employee` set password='$new_pw' where Emp_id = '$id'";
                $result1 = mysqli_query($con, $sql1);
                if ($result1){
                    $success = 1;
                }
                else{
                    die(mysqli_error($con));
                }
            }
            else {
                $dup = 1;
            }
        }

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
  <?php
      if($success){
        echo '<div class="alert alert-success" role="alert">
        <strong>Success </strong> the password was successfully changed!.
      </div>';
      }
    ?>
    <?php
      if($invalid){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> cannot have an empty password.
      </div>';
      }
    ?>
    <?php
      if($dup){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> cannot use old password as new password.
      </div>';
      }
    ?>
    <?php
      if($retryError){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> new passwords do not match.
      </div>';
      }
    ?>
    <?php
      if($oldError){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> old password does not match.
      </div>';
      }
    ?>
    <div class="d-flex justify-content-between">
        <a href="profile.php" class="btn btn-primary m-2">Back</a>
        <a href="logout.php" class="btn btn-primary m-2">Logout</a>
    </div>
    <div class="container mt-5">
      <h5 class = "mb-3">Change password</h5>
    <form method = "post">
      <div class="mb-3">
          <label class="form-label">Enter old password</label>
          <input type="password" class="form-control" placeholder="Old password" name="old_pw_form">
          <label class="form-label mt-3">Enter new password</label>
          <input type="password" class="form-control" placeholder="New password" name="new_pw">
          <label class="form-label mt-3">Enter new password again</label>
          <input type="password" class="form-control" placeholder="New password" name="new_pw_retry">
          <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-primary" type="submit" name="change">Change Password</button>
          </div>  
      </div>
    </form>
    </div>
  </body>
</html>