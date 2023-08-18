<?php
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
    $invalid = 0;
    $dup = 0;
    $success = 0;
    if(isset($_POST['add'])){
        $type = $_POST['type'];
        $size = $_POST['size'];
        $gender = $_POST['gender'];
        $desc = $_POST['desc'];
        $qty = $_POST['quantity'];
        $id = $_SESSION['Emp_id']; //supervisor is who ever is making the clothe
        
        if(empty($type) || empty($size) || empty($gender) || empty($desc)|| empty($qty) || $qty == 0){
            $invalid = 1;
        } else {
            $sql = "Select * from `clothing_inventory` where type='$type' and size='$size' and gender='$gender'";
            $result = mysqli_query($con, $sql);
            if($result){ //check if clothing exists
                $num=mysqli_num_rows($result);
                if($num > 0) {
                    $dup = 1;
                } else {
                    $sql ="insert into `clothing_inventory` (type, size, gender, qty)
                    values ('$type', '$size', '$gender', '$qty')";
                    $result =mysqli_query($con, $sql); //insert into clothing_inventory first
                    
                    $sql1 ="insert into `replenish_c` (Semp_id, type, size, gender)
                    values ('$id', '$type', '$size', '$gender')";
                    $result1 =mysqli_query($con, $sql1); //insert into replenish_c second

                    $result2; 
                    for($x=0; $x < $qty; $x++){ //insert into clothe third
                      $c_id = GUID();
                      $sql2 ="insert into `clothe` (Clothe_id, type, size, gender, description)
                      values ('$c_id','$type', '$size', '$gender', '$desc')";
                      $result2 =mysqli_query($con, $sql2);
                      if(!$result2){
                        die(mysqli_error($con));
                      }
                    }
                    

                    if($result && $result1 && $result2){
                        $success = 1;
                    } else {
                        die(mysqli_error($con));
                    }
                }
            }
        }

    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Clothing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <?php
      if($success){
        echo '<div class="alert alert-success" role="alert">
        The clothing <strong>'.$type.'</strong> was successfully created!.
      </div>';
      }
    ?>
    <?php
      if($invalid){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> Please fill in the information for all the fields and quantity must be greater than 0.
      </div>';
      }
    ?>
    <?php
      if($dup){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error </strong> This clothing already exists (same type, size, gender).
      </div>';
      }
    ?>
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
        <form action="add-clothe.php" method="post">
            <div class="mb-3">
                <label class="form-label">Clothing type (e.g. Hoodie, T-shirt, etc)</label>
                <input type="text" class="form-control" placeholder="Enter clothing type" name="type">

                <label class="form-label mt-4">Select the size</label>
                <select name="size" class="form-select" multiple aria-label="multiple select example">
                <option selected value="XXS">XXS</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
                </select>

                <label class="form-label mt-4">Select the gender</label>
                <select name="gender" class="form-select" multiple aria-label="multiple select example">
                <option selected value="M">Male</option>
                <option value="F">Female</option>
                <option value="U">Unisex</option>
                </select>

                <label class="form-label mt-4">Clothing description</label>
                <input type="text" class="form-control" placeholder="Enter a short description" name="desc">

                <label class="form-label mt-4">Quantity</label>
                <input type="number" class="form-control" placeholder="Enter the quantity" name="quantity">

                <div class="form-text mt-4">You will be supervising this item.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="add">Add clothing</button>
        </form>
    </div>
    
  </body>
</html>