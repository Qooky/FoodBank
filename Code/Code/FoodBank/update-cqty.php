<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != "Supervisor"){
        header('location:signin.php');
    }
?>
<?php
  //function to create guid https://stackoverflow.com/questions/21671179/how-to-generate-a-new-guid#:~:text=php%20function%20guid()%7B%20if,)%2C%204))%3B%20%7D%20%3F%3E
  function GUID()
  {
      if (function_exists('com_create_guid') === true)
      {
          return trim(com_create_guid(), '{}');
      }
  
      return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }  
?>
<?php
    include 'connect.php';
    $type = $_GET['updatetype'];
    $size = $_GET['updatesize'];
    $gender = $_GET['updategender'];
    $prev_qty;
    $sql0 = "Select * 
    from `clothing_inventory`
    where type='$type' and size='$size' and gender = '$gender'";
    $result=mysqli_query($con, $sql0);
    if($result){
      $row=mysqli_fetch_assoc($result);
      $prev_qty = $row['qty'];
    }

    if(isset($_POST['update'])){
      $qty = $_POST['qty'];
      
        $update_qty = $qty-$prev_qty;


        if($update_qty > 0){ //adding
          $result2;

          $sql1="select *
          from `clothe`
          where type='$type' and size='$size' and gender = '$gender'";
          $result1 = mysqli_query($con, $sql1);
          $row=mysqli_fetch_assoc($result1);
          $desc = $row['description'];

          for($x=0; $x < $update_qty; $x++){ //insert into food 
            $c_id = GUID();
            $sql2 ="insert into `clothe` (Clothe_id, type, size, gender, description)
            values ('$c_id','$type', '$size', '$gender', '$desc')";
            $result2 =mysqli_query($con, $sql2);
            if(!$result2){
              die(mysqli_error($con));
              break;
            }
          }

          $sql3="select COUNT(Clothe_id) as qty 
          from `clothe`
          where type='$type' and size='$size' and gender = '$gender' and Corder_no IS NULL";
          $result3 = mysqli_query($con, $sql3);
          $row3=mysqli_fetch_assoc($result3);
          $count = $row3['qty'];

          $sql="update `clothing_inventory` set qty='$count' where type='$type' and size='$size' and gender = '$gender'";
          $result = mysqli_query($con, $sql);



          if($result && $result1 && $result2 && $result3){
            header('location:replenishC.php');
          } else {
            die(mysqli_error($con));
          }
          
        } else if ($update_qty == 0){ //when its the same
          header('location:replenishC.php');
        }else{ //minus
          $update_qty *= -1;

          $sql3="select COUNT(Clothe_id) as qty
          from `clothe`
          where type='$type' and size='$size' and gender = '$gender'";
          $result3 = mysqli_query($con, $sql3);
          $row3=mysqli_fetch_assoc($result3);
          $count = $row3['qty'];

          if($update_qty == $prev_qty && $count==$prev_qty){ //when its 0
            $sql="delete from `clothe` 
            where type='$type' and size='$size' and gender = '$gender' 
            limit $update_qty";
            $result = mysqli_query($con, $sql);

            $sql1="delete from `replenish_c` 
            where type='$type' and size='$size' and gender = '$gender'";
            $result1 = mysqli_query($con, $sql1);

            $sql2="delete from `clothing_inventory` 
            where type='$type' and size='$size' and gender = '$gender'";
            $result2 = mysqli_query($con, $sql2);
            if($result && $result1 && $result2){
              header('location:replenishC.php');
            } else {
              die(mysqli_error($con));
            }
          } else{
            $sql1="delete from `clothe` 
            where type='$type' and size='$size' and gender = '$gender' and Corder_no IS NULL 
            limit $update_qty";
            $result1 = mysqli_query($con, $sql1);

            $sql3="select COUNT(Clothe_id) as qty 
            from `clothe`
            where type='$type' and size='$size' and gender = '$gender' and Corder_no IS NULL";
            $result3 = mysqli_query($con, $sql3);
            $row3=mysqli_fetch_assoc($result3);
            $count = $row3['qty'];

            $sql="update `clothing_inventory` set qty='$count' 
            where type='$type' and size='$size' and gender = '$gender'";
            $result = mysqli_query($con, $sql);

            if($result && $result1 && $result3){
              header('location:replenishC.php');
            } else {
              die(mysqli_error($conn));
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
    <title>Update Clothing Quantity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
    <div class="d-flex justify-content-between">
      <a href="replenishC.php" class="btn btn-primary m-2">Back</a>  
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
        <form method="post">
            <div class="mb-3">
                <?php
                  echo '<label class="form-label">Current <strong>'.$type.' (size: '.$size.' gender: '.$gender.')</strong> quantity: '.$prev_qty.'</label>';
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