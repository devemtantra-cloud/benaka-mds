<?php
include 'header.php';
include 'database.php';
?>

<style>
    <style>.geeks {
        width: 300px;
        height: 300px;
        overflow: hidden;
        margin: 0 auto;
    }

    .geeks img {
        width: 150%;
        transition: 0.5s all ease-in-out;
    }

    .geeks:hover img {
        transform: scale(1.8);
    }
</style>
<!--======= BANNER =========-->
<div class="sub-banner">
    <div class="container">
        <h2>Services</h2>
        <ul class="links">
            <li><a href="index.php">Home</a>/</li>
            <li><a href="service.php">Services</a></li>
        </ul>
    </div>
</div>

<?php
// echo $status=$_GET['status'];
?>
<input type="hidden" id="status" value='<?php echo isset($_GET["status"]) ? htmlspecialchars($_GET["status"], ENT_QUOTES) : ""; ?>'>
<!--======= CONTENT START =========-->
<div class="content">

    <!--======= ABOUT =========-->
    <section class="about">
        <div class="container">
            

            <!--======= ABOUT INFO =========-->
            <div class="about-us">

                <ul class="row">
                    <li class="col-sm-8">
                        <p> Services </p>
                        <p class="margin-t-20">
                            <i class="fa fa-check-circle theme-blue"></i> <a href="driving_school_d.php">Driving school</a>
                        </p>
                        <p><i class="fa fa-check-circle theme-blue"></i><a href="v_insurance_d.php">Vehicle Insurance</a></p>
                        <p><i class="fa fa-check-circle theme-blue"></i><a href="v_loan_d.php">Vehicle Loan</a></p>
                        <p><i class="fa fa-check-circle theme-blue"></i><a href="emission_d.php">Emission Analysis</a></p>
                        <p><i class="fa fa-check-circle theme-blue"></i><a href="auto_c_d.php">Auto Consultancy</a></p>
                        <ul class="row about-img">
                          
                        </ul>
                </ul>
            </div>
        </div>


    </section>
   

    <?php include "footer.php" ?>