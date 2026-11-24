<?php
 include 'header.php';
 include 'database.php';
?>

<!--======= BANNER =========-->
<div class="sub-banner">
    <div class="container">
        <h2>Vehicle loan facility</h2>
        <ul class="links">
            <li><a href="index.php">Home</a>/</li>
            <li><a href="v_loan_d.php">Vehicle Loan</a></li>
        </ul>
    </div>
</div>

<!--======= CONTENT START =========-->
<div class="content">

    <!--======= ABOUT =========-->
    <section class="about">
        <div class="container">
            <!--======= TITTLE =========-->
             <div class="tittle justify">
                <p id="font_style">We have an agreement with 20+ banks to arrange for a vehicle loan at a very low cost.

                </p>
                <hr>
            </div>

   <div class="about-us justify">
                <ul class="row">
             <?php  
           

                      $sel ="SELECT * FROM `rules` WHERE `page`='Veichle Loan'";
                          $result = mysqli_query($con, $sel);
                        while($row6 = mysqli_fetch_array($result)){
                           $menu= $row6[0];
                           $head= $row6[2];
                           $point= $row6[3];
                           $arr_ph = explode("~",$point);

                            //foreach loop to display the returned array
                          
                                ?>

                              
                    <li class="col-sm-8">
                        <h5 class="theme-blue"> <?php echo $head; ?> </h5>
                        <?php 
                              foreach($arr_ph as $i){?>
                        <p class="margin-t-20"><i class="fa fa-check-circle theme-blue"></i> 
                            <?php
                        echo $i; ?>
                         </p>
                          <?php } ?>
                    </li>
                                <?php 
                        }  
             ?>    
          

        </div>
    </section>

    <!--======= LICENSES =========-->
  <!--   <section class="license">
        <div class="container"> -->

            <!--======= LICENSE ROW =========-->

            <!-- <ul class="row"> -->
                <!--======= A LICENSE =========-->
            <!--     <li class="col-sm-6 col-md-3">
                    <div class="icon-over"> <i class="fa fa-motorcycle"></i> </div>
                    <div class="lis-img"> <img src="images/lis-img-1.png" alt=""> </div>
                    <h5>Motor Cycle License</h5>
                    <hr>

                </li> -->

                <!--======= B LICENSE =========-->
             <!--    <li class="col-sm-6 col-md-3">
                    <div class="icon-over"> <i class="fa fa-car"></i> </div>
                    <div class="lis-img"> <img src="images/lis-img-2.png" alt=""> </div>
                    <h5>Car License</h5>
                    <hr>

                </li> -->

                <!--======= C LICENSE =========-->
               <!--  <li class="col-sm-6 col-md-3">
                    <div class="icon-over"> <i class="fa fa-bus"></i> </div>
                    <div class="lis-img"> <img src="images/lis-img-1.png" alt=""> </div>
                    <h5>Heavy Vehicle License</h5>
                    <hr>

                </li> -->

                <!--======= C LICENSE =========-->
                <!-- <li class="col-sm-6 col-md-3">
                    <div class="icon-over"> <i class="fa fa-cab"></i> </div>
                    <div class="lis-img"> <img src="images/lis-img-2.png" alt=""> </div>
                    <h5>Commercial License</h5>
                    <hr>

                </li> -->

            <!-- </ul> -->

            <!--======= SEE ALL LICENSE =========-->
            <!--<div class="text-center"> <a href="#." class="btn">See All LICENSES</a> </div>-->

        <!-- </div>
    </section> -->



    <!--======= QUOTE =========-->
    <section class="quote-sim" style="display: none;">
        <div class="container">
            <h3 class="pull-left">Are You Looking For A Driving School In Manhattan/NYC</h3>
            <!--======= GET A QUOTE BUTTOn =========-->
            <a class="pull-right btn margin-t-20" href="#.">GET A QUOTE</a>
        </div>
    </section>

    <?php include "footer.php" ?>