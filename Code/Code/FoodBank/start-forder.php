<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }
    include 'connect.php';
    $emp = $_SESSION['Emp_id'];
    $sql = "Select * from `employee` where Emp_id = '$emp'";
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
    if(isset($_POST['update-fam'])){
        $adult_m = $_POST['males-num'];
        $adult_f = $_POST['females-num'];
        $child = $_POST['child-num'];
        $sql1 = "update `familytemp$emp` set num='$adult_m' where member='adult_m'";
        $sql2 = "update `familytemp$emp` set num='$adult_f' where member='adult_f'";
        $sql3 = "update `familytemp$emp` set num='$child' where member='child'";
        $x=mysqli_query($con, $sql1);
        $y=mysqli_query($con, $sql2);
        $z=mysqli_query($con, $sql3);

    }
?>

<?php
    if(isset($_POST['remove'])){
        $name = $_POST['remove'];
        $sql="delete from `ordertemp$emp` where name='$name'";
        $result = mysqli_query($con, $sql);
        if(!$result){
            die(mysqli_error($con));
        }

    }
?>


<?php
    if(isset($_POST['qty']) && isset($_POST['name'])){
        $nam = $_POST['name'];
        $q = $_POST['qty'];
        $sql = "update `ordertemp$emp` set qty='$q' where name='$nam'";
        $result =mysqli_query($con, $sql);
        if(!$result){
            die(mysqli_error($con));
        }
    }
?>

<!-- create temp table for adding food -->
<?php
    $home = $_GET['home'];
    if($home){
        $sql = "DROP TABLE IF EXISTS ordertemp$emp";
        $result =mysqli_query($con, $sql); 
        if($result){
            $sql1 = "create table `ordertemp$emp` (
                `name` varchar(100) NOT NULL REFERENCES `food_inventory`(`name`),
                `qty` int(10) unsigned NOT NULL,
                PRIMARY KEY (`name`));";
            $result1 =mysqli_query($con, $sql1);
            if(!$result1){
                die(mysqli_error($con));
            }  
        } else {
            die(mysqli_error($con));
        }
    }
?>
<!-- create temp table for adding family -->
<?php
    $home = $_GET['home'];
    if($home && !isset($_POST['update-fam'])){
        $sql = "DROP TABLE IF EXISTS familytemp$emp";
        $result =mysqli_query($con, $sql); 
        if($result){
            $sql1 = "create table `familytemp$emp` (
                `member` varchar(10) NOT NULL,
                `num` int(10) unsigned NOT NULL,
                `cal` int(10) unsigned NOT NULL,
                PRIMARY KEY (`member`));";
            $result1 =mysqli_query($con, $sql1);
            if($result1){
                $sql2 = "insert into `familytemp$emp` (`member`, `num`, `cal`) values ('adult_m', '1', '2500');";
                $sql3 = "insert into `familytemp$emp` (`member`, `num`, `cal`) values ('adult_f', '1', '2000');";
                $sql4 = "insert into `familytemp$emp` (`member`, `num`, `cal`) values ('child', '1', '1600');";
                mysqli_query($con, $sql2);
                mysqli_query($con, $sql3);
                mysqli_query($con, $sql4);
                
            } else {
                die(mysqli_error($con));
            }
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
    <title>Create food order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="start-forder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>
  <body>  
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

    <h1 class="text-center mt-5">Calgary Food Bank</h1> 
    <div class="container mt-5">
        <form method="post">
            <div class="mb-3">
                <h5 class="mt-3">Family members</h5> 
                <label>Adult males <small>(2500 calories per male)</small></label>
                <select name="males-num" id="males-num" class="form-control">
                    <?php
                        $sql = "Select * from `familytemp$emp` where member='adult_m'";
                        $result =mysqli_query($con, $sql);
                        $row=mysqli_fetch_assoc($result);
                        $m = $row['num'];

                        $x = 0;
                        while($x < 6){
                            if($x == $m)
                                echo "<option selected value='$x'>$x</option>";
                            else
                                echo "<option value='$x'>$x</option>";
                            $x += 1;
                        }
                    ?>
                </select>
                <label class="mt-2">Adult females <small>(2000 calories per female)</small></label>
                <select name="females-num" id="females-num" class="form-control">
                    <?php
                        $sql = "Select * from `familytemp$emp` where member='adult_f'";
                        $result =mysqli_query($con, $sql);
                        $row=mysqli_fetch_assoc($result);
                        $f = $row['num'];
                        
                        $x = 0;
                        while($x < 6){
                            if($x == $f)
                                echo "<option selected value='$x'>$x</option>";
                            else
                                echo "<option value='$x'>$x</option>";
                            $x += 1;
                        }
                    ?>
                </select>
                <label class="mt-2">Children <small>(1600 calories per child)</small></label>
                <select name="child-num" id="child-num" class="form-control">
                    <?php
                        $sql = "Select * from `familytemp$emp` where member='child'";
                        $result =mysqli_query($con, $sql);
                        $row=mysqli_fetch_assoc($result);
                        $c = $row['num'];

                        $x = 0;
                        while($x < 11){
                            if($x == $c)
                                echo "<option selected value='$x'>$x</option>";
                            else
                                echo "<option value='$x'>$x</option>";
                            $x += 1;
                        }
                    ?>
                </select>
                <div class="d-flex flex-column">
                <label class="mt-4">Total calories for family: <strong>
                    <?php
                        $total = 0;
                        $sql = "Select * from `familytemp$emp`";
                        $result =mysqli_query($con, $sql);
                        while($row=mysqli_fetch_assoc($result)){
                            if($row['member'] == 'adult_m'){
                                $total += $row['num'] * $row['cal'];
                            } else if ($row['member'] == 'adult_f'){
                                $total += $row['num'] * $row['cal'];
                            } else {
                                $total += $row['num'] * $row['cal'];
                            }
                        }
                        echo $total; 
                    ?>
                calories</strong></label>
                <button type="submit" class="btn btn-secondary mt-2 mb-3" style="width: 10rem" name="update-fam">Update family</button>
                </div>
                <h5 class="mt-5">Select foods</h5>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" aria-haspopup="true"  data-bs-toggle="dropdown" aria-expanded="false">
                        Type of food
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                            $sql = "Select distinct type from `food`";
                            $result=mysqli_query($con, $sql);
                            if($result){
                                while($row=mysqli_fetch_assoc($result)){
                                    $type = $row['type'];
                                    $adr = "list-food.php?type=$type";
                                    if($type == 'FV') {
                                        $type = "Fruit and Vegetables";
                                    }
                                    echo '<a class="dropdown-item" href="'.$adr.'">'.$type.'</a>';
                                }
                            }
                        ?>
                    </div>
                </div>

                <h5 class="mt-5">Order</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Calories</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "Select * from `ordertemp$emp`";
                            $result=mysqli_query($con, $sql);
                            if($result){
                                while($row=mysqli_fetch_assoc($result)){
                                    $name = $row['name'];
                                    $order_qty = $row['qty'];

                                    $sql1 = "Select distinct * 
                                    from `food` as f, `food_inventory` as fi
                                    where f.name=fi.name and fi.name='$name'";
                                    $result1=mysqli_query($con, $sql1);
                                    if($result1){
                                        $row1=mysqli_fetch_assoc($result1);
                                        $type = $row1['type'];
                                        $cal = $row1['calories'];
                                        $invent_quantity = $row1['qty'];
                                        echo '<tr>
                                                <th scope="row">'.$name.'</th>
                                                <td>'.$type.'</td>
                                                <td>'.$cal.'</td>
                                                <td>
                                                <select class="form-control" id="'.$name.'" style="width: 4rem;">';
                                        

                                        $x = 1;
                                        while($x <= $invent_quantity){
                                            if($x == $order_qty)
                                                echo "<option selected value='$x'>$x</option>";
                                            else
                                                echo "<option value='$x'>$x</option>";
                                            $x += 1;
                                        }
                                        $function = "changeQty('$name');";
                                        echo '</select>
                                            </td>
                                            <td>
                                                <button onclick="'.$function.'" class="btn btn-secondary">update</button>
                                                <button value='.$name.' name="remove" class="btn btn-danger">remove</button>
                                            </td>
                                        </tr>';
                                    } else {
                                        die(mysqli_error($con));
                                        break;
                                    }
                                }
                            } else {
                                die(mysqli_error($con));
                            }
                        ?>
                    </tbody>
                </table>

                <label class="mt-2">Total calories for order: <strong>
                <?php
                    $total = 0;
                    $sql = "Select * from `ordertemp$emp`";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $name = $row['name'];
                            $qty = $row['qty'];
                            $sql1 = "Select distinct * from `food` where name = '$name';";
                            $result1=mysqli_query($con, $sql1);
                            if($result1){
                                $row=mysqli_fetch_assoc($result1);
                                $cal = $row['calories'];
                                $total += $qty * $cal;

                            } else {
                                die(mysqli_error($con));
                                break;
                            }


                        }
                    } else { 
                        die(mysqli_error($con));
                    }
                    echo $total;
                ?> calories</strong></label>


                   

                       
            </div>
            <div class="d-flex justify-content-between mt-5 mb-5">
                <a href="front-home.php" class="btn btn-danger">Cancel order</a>
                <a href="preview-forder.php" class="btn btn-primary
                <?php
                    $sql = "Select * from `ordertemp$emp`";
                    $result = mysqli_query($con, $sql);
                    if($result){
                        $num=mysqli_num_rows($result);
                        if($num <= 0){
                            echo " disabled";
                        }
                    } else {
                        die(mysqli_error($con));
                    }
                ?>
                ">Preview order</a>
            </div>
            
        </form>
        
    </div>

    
  </body>
</html>