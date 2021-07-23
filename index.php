<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//ini_set('auto_detect_line_endings', true);
//ini_set('max_execution_time', 60000);
error_reporting(E_ALL);

include("main.class.php");

$state="";
if (isset($_REQUEST["state"])){$state=$_REQUEST["state"];}
if (!$state){$state="start";}
if(!$state && isset($_SESSION["active"])){
  $state="scan";
}

$main=new main();

if($state=="scan" && !isset($_SESSION["active"])){
  $main->initiate();
}
if($state=="readBarcode"){
  $main->readBarcodeNetworkId();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Summit last copy checker</title>

  <link rel="icon" href="pie_icon.gif" type="gifF" />

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">weeding</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Summit Last Copy Checker
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <!--
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>-->
        </ul>
      </div>

    </div>

  </nav>

  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
      <h5 class="display-2">Summit Last Copy Checker</h5>

    </header>
    <div class="container">
<?php
if(isset($_SESSION["flash"])){
  $type=$_SESSION["flashtype"];

?>
  <div class="alert alert-<?= $type ?>" role="alert">
  <?= $_SESSION["flash"]?>
</div>
  <?php
  unset($_SESSION["flashtype"]);
  unset($_SESSION["flash"]);
}



 ?>


    </div>

    <!-- Page Features -->

<?php

$main->switchboard($state);


//echo $state;
 ?>



    <div class="row text-center">



    </div>

    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 ">
    <div class="container">
      <p class="m-0 text-center text-white"></p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>
