<?php
    include 'connect.php';
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }
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

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
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

        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Calories</th>
                <th scope="col">Quantity</th>
                <th scope="col">Operation</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $type = $_GET['type'];
                    $sql = "Select distinct fi.name, fi.qty, f.calories 
                    from `food` as f, `food_inventory` as fi
                    where f.name = fi.name and f.type = '$type'";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $name = $row['name'];
                            $qty = $row['qty'];
                            $cal = $row['calories'];
                            echo '<tr>
                                <th scope="row">'.$name.'</th>
                                <td>'.$cal.'</td>
                                <td>'.$qty.'</td>
                                <td>';
                            $sql1 = "Select * from `ordertemp$emp` where name='$name'";
                            $result1 = mysqli_query($con, $sql1);
                            if($result1){ //check if food exists in order
                                $num=mysqli_num_rows($result1);
                                if($num > 0) {
                                    echo '<a href="food-to-ordertemp.php?name='.$name.'&add=0&type='.$type.'" class="btn btn-danger">remove</a>
                                    </td>
                                    </tr>';
                                } else {
                                    echo '<a href="food-to-ordertemp.php?name='.$name.'&add=1&type='.$type.'" class="btn btn-success">add</a>
                                        </td>
                                        </tr>';
                                }
                            } else {
                                die(mysqli_error($con));
                            }
                        }
                    } else {
                        die(mysqli_error($con));
                    }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <a href="start-forder.php?home=0" class="btn btn-primary m-2">Done</a>
        </div>
        
    </div>
  </body>
</html>