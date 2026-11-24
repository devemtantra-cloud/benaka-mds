
<?php
include "database.php";
   $sel="SELECT * FROM `font`";
                   $result = mysqli_query($con,$sel);
                  $i=1;
                 $row = mysqli_fetch_array($result); 
                  
                   $style= $row[1];


 ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from jthemes.net/themes/f-html/driveme/drive-me/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 22 Feb 2022 09:23:24 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <title>BMDS</title>
    <link rel="icon" href="images/favicon.ico" title="Benaka driving school">

    <meta name="keywords" content="Benaka driving school">
    <meta name="description" content="Drive Me - Benaka driving school">
    <meta name="author" content="Benaka driving school">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- FONTS ONLINE -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:500,900,300,700,400' rel='stylesheet' type='text/css'>

    <!--MAIN STYLE-->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css">
    <link href="css/main.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/responsive.css" rel="stylesheet" type="text/css">
    <link href="css/animate.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <a href="https://api.whatsapp.com/send?phone=+919591495418&text=Hi, Benaka Moto Driving School" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <!--======= COLOR STYLE CSS =========-->
    <link rel="stylesheet" id="color" href="css/color/default.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<style type="text/css">
    #font_style{
        font-family: <?php echo $style; ?>
    }
</style>
<body id="font_style">
    <!--======= PRELOADER =========-->
    <div class="work-in-progress">
        <div id="preloader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!--======= COLOR SWITCHER =========-->


    <!--======= WRAPPER =========-->
    <div id="wrap">

        <!--======= TOP BAR =========-->
        <div class="top-bar">
            <div class="container">

                <ul class="right-bar-side">

                    <li> <a href="tel:08276298585"><i class="fa fa-phone"></i> 082762 98585</a></li>
                    <li> <a href="mailto:benakamotodrive@gmail.com"><i class="fa fa-envelope"></i>benakamotodrive@gmail.com </a></li>
                </ul>
            </div>
        </div>

        <!--======= HEADER =========-->
        <header class="sticky">
            <div class="container">
                <nav class="navbar navbar-default">
                    <div class="row">
                        <div class="navbar-header col-md-3 col-sm-3">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-res"> <span class="sr-only">Toggle navigation</span> <span class="fa fa-navicon"></span> </button>
                            <!--======= LOGO =========-->
                            <div class="logo"> <a href="index.php"><img src="images/benaka_logo2.jpeg" style="margin-top: -5px;"> </a> </div>
                        </div>

                        <!--======= MENU =========-->
                        <div class="col-md-9 col-sm-9">
                            <div class="collapse navbar-collapse" id="nav-res">

                                <ul class="nav navbar-nav">
                                    <li class="dropdown"> <a href="index.php"><span class="theme-blue">Home</span></a>
                                    </li>

                                    <li class="dropdown"> <a href="about_us.php"><span class="theme-blue">About</span></a>
                                    </li>
                                     


                                    <li class="dropdown"> <a href="service.php"><span class="theme-blue">Services</span></a>
                                         <a href="service.php" id="set"  target="blank" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label"></span> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="driving_school_d.php">Driving School</a></li>
                                        <li><a href="v_insurance_d.php">Vehicle Insurance</a></li>
                                         <li><a href="v_loan_d.php">Vehicle Loan</a></li>
                                        <li><a href="emission_d.php">Emission Analysis</a></li>
                                         <li><a href="auto_c_d.php">Auto Consultancy</a></li>
                                    </ul>
                                    </li>

                                    <li><a href="contact.php"><span id='cnt' class="theme-blue">Contact</span></a></li>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <style type="text/css">
            a#set {
    position: relative;
    left: -35px;
}
@media only screen and (max-width: 600px) {
a#set {
    position: relative;
    left: 7px;
    top: -19px;
}
span#cnt {
    position: relative;
    top: -41px;
}
span.caret {
    position: relative;
    top: -21px;
    left: 30px;
}
.navbar-nav .open .dropdown-menu > li > a {
    line-height: 18px;
    top: -26px;
    position: relative;
}
}
        </style>