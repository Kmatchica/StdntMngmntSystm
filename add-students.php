<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else {
  if(isset($_POST['submit'])){
    $stuname=$_POST['stuname'];
    $stuclass=$_POST['stuclass'];
    $gender=$_POST['gender'];
    $dob=$_POST['dob'];
    $age=$_POST['age'];
    $fname=$_POST['fname'];
    $mname=$_POST['mname'];
    $connum=$_POST['connum'];
    $address=$_POST['address'];
    
    // $image=$_FILES["image"]["name"];
    // $extension = substr($image,strlen($image)-4,strlen($image));
    // $allowed_extensions = array(".jpg","jpeg",".png",".gif");
    // if(!in_array($extension,$allowed_extensions))
    // {
    //   echo "<script>alert('Logo has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
    // }
    // else
    // {
    //   $image=md5($image).time().$extension;
    // move_uploaded_file($_FILES["image"]["tmp_name"],"images/".$image); //Query here
    // }  //For image uploading future reference


    //TEMPLATING STUDENT ID
    $sectionSql = "select ClassName, schoolYear FROM tblclass WHERE ID = :stuclass";
    $sectionQuery=$dbh->prepare($sectionSql);
    $sectionQuery->bindParam(':stuclass',$stuclass,PDO::PARAM_STR);
    $sectionQuery-> execute();
    $sectionRow = $sectionQuery -> fetch(PDO::FETCH_OBJ);
    
    //SECTION PART
    $words = explode(" ", $sectionRow->ClassName);
    $sectionFirstLetters = [];
    foreach ($words as $word) {
      $sectionFirstLetters[] = substr($word, 0, 1);
    }
    //SY PART
    $last_two_digits_string = substr($sectionRow->schoolYear, -2); //SY
    $sectionFirstLettersString = implode("", $sectionFirstLetters); //Section
    // END TEMPLATING ID

    $sql="insert into tblstudent(StudentName,StudentClass,Gender,DOB,age,FatherName,MotherName,ContactNumber,Address)values(:stuname,:stuclass,:gender,:dob,:age,:fname,:mname,:connum,:address)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':stuname',$stuname,PDO::PARAM_STR);
    $query->bindParam(':stuclass',$stuclass,PDO::PARAM_STR);
    $query->bindParam(':gender',$gender,PDO::PARAM_STR);
    $query->bindParam(':dob',$dob,PDO::PARAM_STR);
    $query->bindParam(':age',$age,PDO::PARAM_STR);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':mname',$mname,PDO::PARAM_STR);
    $query->bindParam(':connum',$connum,PDO::PARAM_STR);
    $query->bindParam(':address',$address,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
      //TEMPLATING ID QUERY
      $paddedId = str_pad($LastInsertId, 4, '0', STR_PAD_LEFT);
      $stuid = "MILC" ."-".$sectionFirstLettersString."-".$last_two_digits_string."-".$paddedId; //MILC-SG-24-0001
      $sqlStuid = "UPDATE  tblstudent SET StuID = :stuid WHERE ID = :LastInsertedId";
      $queryStuid=$dbh->prepare($sqlStuid);
      $queryStuid->bindParam(':stuid',$stuid,PDO::PARAM_STR);
      $queryStuid->bindParam(':LastInsertedId',$LastInsertId,PDO::PARAM_STR);
      $queryStuid->execute();
      //END TEMPLATING ID QUERY
      echo '<script>alert("Student has been added.")</script>';
      echo "<script>window.location.href ='add-students.php'</script>";
    }
    else{
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
  }
  ?>
  
      <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Students </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> <?php echo $first_letters_string; // Output: SJ ?></li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                   
                    <form class="forms-sample row" method="post" enctype="multipart/form-data" >
                      
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Student Name</label>
                        <input type="text" name="stuname" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail3">Student Class</label>
                        <select  name="stuclass" class="form-control" required='true'>
                          <option value="">Select Section</option>
                         <?php 
                          $sql2 = "SELECT * from    tblclass ";
                          $query2 = $dbh -> prepare($sql2);
                          $query2->execute();
                          $result2=$query2->fetchAll(PDO::FETCH_OBJ);

                          foreach($result2 as $row1)
                          {          
                          ?>  
                            <option value="<?php echo htmlentities($row1->ID);?>"><?php echo htmlentities($row1->ClassName);?> SY <?php echo htmlentities($row1->schoolYear);?></option>
                          <?php 
                          } 
                          ?> 
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Gender</label>
                        <select name="gender" value="" class="form-control" required='true'>
                          <option value="">Choose Gender</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Date of Birth</label>
                        <input type="date" name="dob" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Age</label>
                        <input type="number" name="age" value="" class="form-control" required='true'>
                      </div>
                      <!-- <div class="form-group col-md-6">
                        <label for="exampleInputName1">Student ID</label>
                        <input type="text" name="stuid" value="" class="form-control" required='true'>
                      </div> -->
                      <!-- <div class="form-group col-md-6">
                        <label for="exampleInputName1">Student Photo</label>
                        <input type="file" name="image" value="" class="form-control" required='true'>
                      </div> -->
                      <h3 class="col-md-12 page-title" >Parents/Guardian's details</h3>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Father's Name</label>
                        <input type="text" name="fname" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Mother's Name</label>
                        <input type="text" name="mname" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Contact Number</label>
                        <input type="text" name="connum" value="" class="form-control" required='true' maxlength="10" pattern="[0-9]+">
                      </div>
                      <div class="form-group col-md-12">
                        <label for="exampleInputName1">Address</label>
                        <textarea name="address" class="form-control"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php }  ?>