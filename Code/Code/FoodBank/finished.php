<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }
    include 'connect.php';
?>

<?php
    $order_no = $_GET['o'];
    $fam_no = $_GET['f'];
?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finished Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <!-- <h1 class="text-center mt-5">Calgary Food Bank</h1>  -->
    <div class="container mt-5 w-50">
        <h4 class="text-center">Order has been completed!</h4>
        <h6 class="text-center mt-5">Order number: <?php echo $order_no?></h6>
        <h6 class="text-center">Family number: <?php echo $fam_no?></h6>
    </div>

    <div class="d-flex justify-content-center">
        <a href="front-home.php" class="btn btn-primary m-5">Home</a>
    </div>
    
    
  </body>
</html>