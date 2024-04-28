<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
if(isset($_POST['submit'])){
  $cname=$_POST['cname'];
  $schoolYear=$_POST['schoolYear'];
  $sql="insert into tblclass(ClassName,schoolYear)values(:cname,:schoolYear)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':cname',$cname,PDO::PARAM_STR);
  $query->bindParam(':schoolYear',$schoolYear,PDO::PARAM_STR);
  $query->execute();
  echo $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) {
    echo '<script>alert("Section has been added.")</script>';
    echo "<script>window.location.href ='add-class.php'</script>";
  }else{
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}

$years = [];
$start_year = 2023;
$end_year = 2030;

for ($year = $start_year; $year <= $end_year - 1; $year++) {
    $years[] = $year . "-" . ($year + 1);
}

// Output the array

  ?>

     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Section </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Class</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                    <form class="forms-sample" method="post">
                      
                      <div class="form-group">
                        <label for="exampleInputName1">Section Name</label>
                        <input type="text" name="cname" value="" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Section</label>
                        <select  name="schoolYear" class="form-control" required='true'>
                          <option value="">School Year</option>
                          <?php 
                            foreach($years as $year)
                            { 
                          ?>
                            <option value="<?php echo $year;?>"><?php echo $year;?></option>
                          <?php
                            }
                          ?> 
                        </select>
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
    <!-- plugins:js -->
   <?php }  ?>