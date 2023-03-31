<?php
    include 'connect.php';
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != "Supervisor"){
        header('location:signin.php');
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Replenish Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <div class="d-flex justify-content-between">
        <a href="super-home.php" class="btn btn-primary m-2">Back</a>
        <a href="logout.php" class="btn btn-primary m-2">Logout</a>
    </div>

    <h1 class="text-center mt-5">Calgary Food Bank</h1> 
    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Calories</th>
                <th scope="col">Qty</th>
                <th scope="col">Operation</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $user = $_SESSION['Emp_id'];
                    $sql = "Select fi.name, fi.qty, f.type, f.calories 
                    from `employee` as e, `food_inventory` as fi, `food` as f, `replenish_f` as r
                    where e.Emp_id='$user' and e.Emp_id = r.Semp_id and r.name = fi.name and fi.name = f.name";
                    $result=mysqli_query($con, $sql);
                    if($result){
                        while($row=mysqli_fetch_assoc($result)){
                            $name = $row['name'];
                            $qty = $row['qty'];
                            $type = $row['type'];
                            $cal = $row['calories'];
                            echo '<tr>
                                <th scope="row">'.$name.'</th>
                                <td>'.$type.'</td>
                                <td>'.$cal.'</td>
                                <td>'.$qty.'</td>
                                <td>
                                <a href="update-fqty.php?updatename='.$name.'" class="btn btn-secondary">update</a>
                                </td>
                            </tr>
                            ';
                        }
                    }
                ?>
            </tbody>
        </table>
        
    </div>
  </body>
</html>