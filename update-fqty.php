<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != "Supervisor"){
        header('location:signin.php');
    }
?>
<?php
    include 'connect.php';
    $name = $_GET['updatename'];
    if(isset($_POST['update'])){
      $qty = $_POST['qty'];
      $sql="update `food_inventory` set qty='$qty' where name='$name'";
      $result = mysqli_query($con, $sql);
      if($result){
        header('location:replenishF.php');
      } else {
        die(mysqli_error($conn));
      }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Food Quantity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <div class="d-flex justify-content-between">
        <a href="replenishF.php" class="btn btn-primary m-2">Back</a>
        <a href="logout.php.php" class="btn btn-primary m-2">Logout</a>
    </div>

    <h1 class="text-center mt-5">Calgary Food Bank</h1> 
    <div class="container mt-5">
        <form method="post">
            <div class="mb-3">
                <?php
                  $sql = "Select * 
                  from `food_inventory`
                  where name = '$name'";
                  $result=mysqli_query($con, $sql);
                  if($result){
                    $row=mysqli_fetch_assoc($result);
                    $qty1 = $row['qty'];
                    echo '<label class="form-label">Current <strong>'.$name.'</strong> quantity: '.$qty1.'</label>';
                  }
                ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Set new quantity</label>
                <input type="number" class="form-control" placeholder="Enter new quantity" name="qty">
            </div>
            <button type="submit" class="btn btn-primary w-100" name="update">Update Quantity</button>
        </form>
    </div>
  </body>
</html>