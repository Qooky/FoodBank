<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Front"){
        header('location:signin.php');
    }
    include 'connect.php';
    $order_no = $_GET['ordNo'];
    $page = $_GET['page'];
    $order_type;
    $bemp_id;
    $picked_up;


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


    $sql = "Select * from `order` where Order_no = '$order_no'";
    $result = mysqli_query($con, $sql);
    $row=mysqli_fetch_assoc($result);
    if($result){
      $order_type= $row['type'];
      $bemp_id = $row['Bemp_id'];
      $picked_up = $row['Picked_up'];
    }
    else{
      die(mysqli_error($con));
    }
?>


<?php
    $adult_m_num = 0;
    $adult_f_num = 0;
    $child_num = 0;
    $total_fam_cal = 0;
    $total_members = 0;

    $sql1 ="Select * from `orders` where `Order_no`='$order_no'";
    $result1 = mysqli_query($con, $sql1);
    $row1=mysqli_fetch_assoc($result1);

    $fam_id=$row1['Fam_id'];
    $femp_id=$row1['Femp_id'];
    $date=$row1['date'];
    $time=$row1['time'];

    $sql2 ="Select count(fam_id) as x from `child` where `Fam_id`='$fam_id'";
    $result2 = mysqli_query($con, $sql2);
    $row2 =mysqli_fetch_assoc($result2);
    $child_num = $row2['x'];

    $sql3 ="Select count(fam_id) as x from `adult` where `Fam_id`='$fam_id' and `gender`='M'";
    $result3 = mysqli_query($con, $sql3);
    $row3 =mysqli_fetch_assoc($result3);
    $adult_m_num = $row3['x'];

    $sql4 ="Select count(fam_id) as x from `adult` where `Fam_id`='$fam_id' and `gender`='F'";
    $result4 = mysqli_query($con, $sql4);
    $row4 =mysqli_fetch_assoc($result4);
    $adult_f_num = $row4['x'];

    $total_members =  $child_num + $adult_f_num + $adult_m_num;
    $total_fam_cal = ($child_num * 1600)+ ($adult_f_num*2000) + ($adult_m_num*2500);

?>

<?php
    $total_order_cals = 0;
    $sql = "Select * from `food` where `Forder_no`='$order_no'";
    $result=mysqli_query($con, $sql);
    if($result){
        while($row=mysqli_fetch_assoc($result)){
            $total_order_cals += $row['calories'];
        }
    } else { 
        die(mysqli_error($con));
    }
?> 



<?php
    $f_fname;
    $f_lname;
    $b_fname;
    $b_lname;

    $sql = "Select * from `employee` where `Emp_id`='$femp_id'";
    $result=mysqli_query($con, $sql);
    if($result){
        $row=mysqli_fetch_assoc($result);
        $f_fname = $row['Fname'];
        $f_lname = $row['Lname'];

    } else { 
        die(mysqli_error($con));
    }

    if($page !="unassigned"){
        $sql = "Select * from `employee` where `Emp_id`='$bemp_id'";
        $result=mysqli_query($con, $sql);
        if($result){
            $row=mysqli_fetch_assoc($result);
            $b_fname = $row['Fname'];
            $b_lname = $row['Lname'];

        } else { 
            die(mysqli_error($con));
        }
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
    <h1 class="text-center mt-5">Preview <?php echo $order_type?> Order</h1> 
    <div class="container mt-5">
        <h5>Employee information</h5>
        <div class="d-flex flex-column mt-3 mb-5 gap-2">
            <label>Front employee name: <?php echo $f_fname?> <?php echo $f_lname?></label>
            <label>Front employee id: <?php echo $femp_id?></label>
            <?php if($page != "unassigned"){
                echo '<label class="mt-2">Back employee name: '.$b_fname.' '.$b_lname.'</label>
                <label>Back employee id: '.$bemp_id.'</label>';
            }   
            
            ?>
        </div>
        <h5>Family information</h5>
        <div class="d-flex flex-column mt-3 mb-5 gap-2">
            <label>Adult males: <strong><?php echo $adult_m_num ?></strong> </label>
            <label>Adult females: <strong><?php echo $adult_f_num ?></strong></label>
            <label>Children: <strong><?php echo $child_num ?></strong></label>
            <label>Total number of family members: <strong><?php echo $total_members ?></strong></label>
            <label>Total calories for family: <strong><?php echo $total_fam_cal ?> calories</strong></label>
        </div>

        

        <h5 class="mb-3">Order information</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Calories</th>
                <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "Select f.name, f.type, f.calories ,COUNT(f.name)
                    from `food` as f
                    where f.Forder_no = $order_no
                    GROUP BY f.name";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $name = $row['name'];
                            $type = $row['type'];
                            $calories = $row['calories'];
                            $qty = $row['COUNT(f.name)'];
                            echo '<tr>
                                <th scope="row">'.$name.'</th>
                                <td>'.$type.'</td>
                                <td>'.$calories.'</td>
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
        <label>Total calories for order: <strong><?php echo $total_order_cals ?> calories</strong></label>
        
        <div class="d-flex justify-content-between mt-5  mb-5">
                <a href="<?php echo $page?>-orders.php" class="btn btn-secondary">Back</a>
                <?php
                    if ($page == "incomplete") {
                        echo '<a href="backemp-complete-order.php?ordNo='.$order_no.'" class="btn btn-primary">Complete order</a>';
                    } else if ($page == "pickup-ready") { 
                        echo '<a href="backemp-complete-pickedup-order.php?ordNo='.$order_no.'" class="btn btn-primary">Picked up</a>';
                    } else if ($page == "unassigned") {
                        echo '<a href="assign-order-back.php?ordNo='.$order_no.'" class="btn btn-primary">Assign to me</a>';
                    }
                ?> 
                
        </div>

        <label class="mt-2 mb-2">Created at: <?php echo $date?> <?php echo $time?></label>
         
    </div>
  </body>
</html>