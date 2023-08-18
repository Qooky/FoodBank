<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
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
    $adult_m_num = 0;
    $adult_f_num = 0;
    $child_num = 0;
    $total_fam_items = 0;
    $total_members = 0;

    $sql ="Select * from `familytemp$id`";
    $result = mysqli_query($con, $sql);
    if($result){
        while($row=mysqli_fetch_assoc($result)){
            $member = $row['member'];
            $num = $row['num'];
            $total_members += $num;
            if($num > 0) {
                $total_fam_items += 5 * $num;
            }
            if($member == "adult_m"){
                $adult_m_num = $num;
            } else if ($member == "adult_f"){
                $adult_f_num = $num;
            } else {
                $child_num = $num;
            }
        }
    }
    else{
      die(mysqli_error($con));
    }
?>

<?php
    $total_order_items = 0;
    $sql = "Select * from `ordertemp$id`";
    $result=mysqli_query($con, $sql);
    if($result){
        while($row=mysqli_fetch_assoc($result)){

            $qty = $row['qty'];
            $total_order_items += $qty;
        }
    } else { 
        die(mysqli_error($con));
    }
?> 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
    <div class="d-flex justify-content-end">
      
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
    <h1 class="text-center mt-5">Preview order</h1> 
    <div class="container mt-5">
        <h5>Employee information</h5>
        <div class="d-flex flex-column mt-3 mb-5 gap-2">
            <label>Employee name: <?php echo $fname?> <?php echo $lname?></label>
            <label>Employee id: <?php echo $id?></label>
        </div>
        <h5>Family information</h5>
        <div class="d-flex flex-column mt-3 mb-5 gap-2">
            <label>Adult males: <strong><?php echo $adult_m_num ?></strong> </label>
            <label>Adult females: <strong><?php echo $adult_f_num ?></strong></label>
            <label>Children: <strong><?php echo $child_num ?></strong></label>
            <label>Total number of family members: <strong><?php echo $total_members ?></strong></label>
            <label>Total items for family: <strong><?php echo $total_fam_items ?> items</strong></label>
        </div>

        

        <h5 class="mb-3">Order information</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Type</th>
                <th scope="col">Size</th>
                <th scope="col">Gender</th>
                <th scope="col">Description</th>
                <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "Select distinct f.type, f.size, f.gender, f.description, o.qty  
                    from `ordertemp$id` as o, `clothe` as f
                    where f.type = o.type and f.size = o.size and f.gender = o.gender";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $type = $row['type'];
                            $size = $row['size'];
                            $gender = $row['gender'];
                            $description = $row['description'];
                            $qty = $row['qty'];
                            echo '<tr>
                                <th scope="row">'.$type.'</th>
                                <td>'.$size.'</td>
                                <td>'.$gender.'</td>
                                <td>'.$description.'</td>
                                <td>'.$qty.'</td>
                            </tr>
                            ';

                        }
                    } else {
                        die(mysqli_error($con));
                    }
                ?>
            </tbody>
        </table>
        <label>Total items for order: <strong><?php echo $total_order_items ?> items</strong></label>
        
        <div class="d-flex justify-content-between mt-5  mb-5">
                <a href="start-corder.php?home=0" class="btn btn-secondary">Edit order</a> 
                <a href="create-corder.php" class="btn btn-primary">Confirm order</a>
        </div>
         
    </div>
  </body>
</html>