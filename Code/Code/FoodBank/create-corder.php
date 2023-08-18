<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == "Back"){
        header('location:signin.php');
    }
    include 'connect.php';
    
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
    date_default_timezone_set('America/Edmonton');
    $time = date('H:i:s');
    $date = date('Y-m-d');

    $id = $_SESSION['Emp_id'];
?>

<!-- Creating family -->
<?php
    $sql = "insert into `family` (`Fam_id`)
    VALUES (NULL)";
    $fam_id;
    if (mysqli_query($con, $sql)) {
        $fam_id = mysqli_insert_id($con);
        $sql ="Select * from `familytemp$id`";
        $result = mysqli_query($con, $sql);
        if($result){
            while($row=mysqli_fetch_assoc($result)){
                $member = $row['member'];
                $num = $row['num'];
                $cal = $row['cal'];
                $counter = 0;
                while($counter < $num){
                    $member_id = GUID();
                    $sql1;
                    if($member == "adult_m"){
                        $sql1 = "insert into `adult` (Fam_id, adult_id, cals_needed, gender) 
                        VALUES ('$fam_id', '$member_id', '$cal', 'M')";
                    } else if ($member == "adult_f"){
                        $sql1 = "insert into `adult` (Fam_id, adult_id, cals_needed, gender) 
                        VALUES ('$fam_id', '$member_id', '$cal', 'F')";
                    } else {
                        $sql1 = "insert into `child` (Fam_id, child_id, cals_needed) 
                        VALUES ('$fam_id', '$member_id', '$cal')";
                    }
                    $result1 = mysqli_query($con, $sql1);
                    if(!$result1){
                        die(mysqli_error($con));
                        break;
                    }
                    $counter += 1;
                }
                
            }
        }
    } else {
        die(mysqli_error($con));
    }

?>

<!-- Inserting Order -->
<?php
    $sql = "insert into `Order` (Picked_up, Bemp_id, type, Ready_for_pick_up)
    values (0, NULL, 'Clothe', 0)";
    $order_id;
    if (mysqli_query($con, $sql)) {
        $order_id = mysqli_insert_id($con);
        $sql1 = "insert into `C_Order`(Corder_no) values ('$order_id')";
        if (!mysqli_query($con, $sql1)){
            die(mysqli_error($con));
        }
      } else {
        die(mysqli_error($con));
      }
?>

<!-- Inserting Orders -->
<?php
    $sql = "insert into `Orders` (Femp_id, Order_no, Fam_id, date, time)
    values ('$id', '$order_id', '$fam_id', '$date', '$time')";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die(mysqli_error($con));
    }
?>

<!-- Inserting order number into food and f_supplies-->
<?php
    $sql = "Select * from `ordertemp$id`";
    $result = mysqli_query($con, $sql);
        if($result){
            while($row=mysqli_fetch_assoc($result)){
                $type = $row['type'];
                $size = $row['size'];
                $gender = $row['gender'];
                $qty = $row['qty'];

                //insert into c_supplies
                $sql1 = "insert into `C_supplies` (Corder_no, type, size, gender) values ('$order_id', '$type', '$size', '$gender')";
                if(mysqli_query($con, $sql1)){
                    $counter = 0;
                    $sql2 = "select * from `clothe` where type='$type' and size='$size' and gender='$gender' and Corder_no IS NULL";
                    $result1 = mysqli_query($con, $sql2);
                    while ($row1=mysqli_fetch_assoc($result1)){
                        if($counter < $qty){
                            $clothe_id = $row1['Clothe_id'];
                            $sql3="update `clothe` set Corder_no='$order_id' where Clothe_id='$clothe_id'";
                            $result2 = mysqli_query($con, $sql3);
                            if(!$result2){
                                die(mysqli_error($con));
                                break;
                            }
                            $counter += 1;
                        } else{
                            break;
                        }
                        
                    }
                } else {
                    die(mysqli_error($con));
                    break;
                }
                $sql4="select COUNT(type) as qty
                from `clothe`
                where type='$type' and  size='$size' and  gender='$gender' and Corder_no IS NULL";
                $result3 = mysqli_query($con, $sql4);
                $row3=mysqli_fetch_assoc($result3);
                $count = $row3['qty'];

                $sql5="update `clothing_inventory` set qty='$count' where type='$type' and size='$size' and gender='$gender'";
                $result4 = mysqli_query($con, $sql5);
            }
        }
?>

<?php
    $sql = "DROP TABLE IF EXISTS ordertemp$id";
    if(mysqli_query($con, $sql)){
        $sql1 = "DROP TABLE IF EXISTS familytemp$id";
        if(mysqli_query($con, $sql1)){
            header("location:finished.php?o=$order_id&f=$fam_id");
        } else {
            die(mysqli_error($con));
        }
    } else {
        die(mysqli_error($con));
    }
?>