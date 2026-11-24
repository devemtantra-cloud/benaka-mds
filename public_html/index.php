<?php
include 'header.php';
include 'database.php';
?>
<!--======= BANNER =========-->
<div id="banner">
    <div class="flexslider">
        <ul class="slides">
            <?php
            $sel = "select * from slider";
            $result = mysqli_query($con, $sel);
            while ($row = mysqli_fetch_array($result)) { ?>
                <!--======= SLIDE 1 =========-->
                <li class="slider-height"> <img src="<?php echo htmlspecialchars(preg_replace('#^https?://[^/]+#i', '', $row['filename'])); ?>" alt="">
                    <div class="container">
                        <div class="text-slider">
                            <div class="col-sm-7">
                                <!-- <h3>BENAKA MOTO DRIVING SCHOOL <i class="fa fa-long-arrow-right"></i></h3>
                                <p>Somwarpet, Karnataka 571236</p> -->
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<!--======= CONTENT START =========-->
<div class="content"  id="font_style">
    <!--======= RESPONSIVE FORM =========-->
    <div class="visible-xs" id="find-course">
        <div class="container">
            <div class="col-xs-12" style="display: none">
                <div class="find-drive">
                    <h5>Find Driving Course <i class="fa fa-road"></i></h5>
                    <div class="drive-form">
                        <div class="intres-lesson">

                            <!--======= FORM =========-->
                            <form role="form" id="find_course" method="post">
                                <ul class="row">

                                    <!--======= INPUT NAME =========-->
                                    <li class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" title="" data-original-title="" class="form-control" id="name" name="Name" placeholder="Your Name">
                                            <span class="fa fa-user"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT EMAIL =========-->
                                    <li class="col-sm-12">
                                        <div class="form-group">
                                            <input type="email" title="" data-original-title="" class="form-control" id="email" placeholder="Your Email">
                                            <span class="fa fa-envelope"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT PHONE NUMBER =========-->
                                    <li class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" title="" data-original-title="" class="form-control" id="phone" placeholder="Phone">
                                            <span class="fa fa-phone"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT PHONE NUMBER =========-->
                                    <li class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" title="" data-original-title="" name="datepicker" class="form-control hasDatepicker" id="datepicker" placeholder="DD/MM/YY">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT SELECT =========-->
                                    <li class="col-sm-6">
                                        <div class="form-group">
                                            <select id="select1">
                                                <option>Course Type</option>
                                                <option>Course Type 1</option>
                                                <option>Course Type 2</option>
                                                <option>Course Type 3</option>
                                            </select>
                                            <span class="fa fa-file-text-o"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT SELECT =========-->
                                    <li class="col-sm-6">
                                        <div class="form-group">
                                            <select id="select2">
                                                <option>Car Type</option>
                                                <option>Car Type 1</option>
                                                <option>Car Type 2</option>
                                                <option>Car Type 3</option>
                                            </select>
                                            <span class="fa fa-car"></span>
                                        </div>
                                    </li>

                                    <!--======= INPUT SELECT =========-->
                                    <li class="col-sm-6">
                                        <div class="form-group">
                                            <select id="select3">
                                                <option>Time</option>
                                                <option>Time 1</option>
                                                <option>Time 2</option>
                                                <option>Time 3</option>
                                            </select>
                                            <span class="fa fa-clock-o"></span>
                                        </div>
                                    </li>
                                </ul>

                                <!--======= BUTTON =========-->
                                <div class="text-center">
                                    <button type="submit" value="submit" class="btn" id="btn_submit">Find MY Course</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- start -->
     <section id="video" data-stellar-background-ratio="0.2">
        <div class="container text-center">
            <h1><span class="theme-blue">We Provide Best Services For You</span></h1>
            <h3 class="theme-blue">Driving lessons and related services</h3>

    </section>
    <!-- end -->
     <section id="pricing">
        <div class="container">
            <!--======= TITTLE =========-->
            <div class="tittle justify" id="font_style">
                <h3><span class="theme-blue">Driving School</span></h3>
                <p  id="font_style"> Our most well-known driving school in Kodagu has more than 12 years of expertise and is run by professional trainers Driving lessons in Kodagu's countryside, towns, and mountain highways. Students are self-assured enough to drive without fear of being caught.</p>
                <br />
                <p class="margin-t-20"  id="font_style"><i class="fa fa-check-circle"></i> Skilled and experienced drivers provide driving instruction</p>
                <p class="margin-t-20"  id="font_style"><i class="fa fa-check-circle"></i> A total of 28 days of driving instruction, with 56 kilometres per lesson</p>
                <p class="margin-t-20"  id="font_style"><i class="fa fa-check-circle"></i> Screen-based illustration on the road instruction projector. This is the district's first attempt at this type of training</p>
                <hr>
            </div>
        </div>
<style>
#feature li a {
    background: white;
    border: 0px solid #dee1e4;
    display: inline-block;
    line-height: 35px;
    padding: 0 20px;
    border-radius: 4px;
    margin-top: 20px;
    -webkit-transition: 0.4s ease-in-out;
    -moz-transition: 0.4s ease-in-out;
    -ms-transition: 0.4s ease-in-out;
    -o-transition: 0.4s ease-in-out;
    transition: 0.4s ease-in-out;
}

</style>
    </section>

    <!--======= FEATURE =========-->
    <section id="feature">

        <div class="container">

            <!-- section start -->
             <!--======= VIDEO =========-->
   

    <!--======= PRICING =========-->
   
            <!-- end -->
            <!--======= TITTLE =========-->
            <div class="tittle">
                <h3><span class="theme-blue" id="font_style">Our Services</span> </h3>
                <p><span class="theme-blue" id="font_style"> Giving Best Options For You </span></p>
                <hr>
            </div>
            <!--======= FEATURE =========-->
            <ul class="row">

                <!--======= SERVICES 1 =========-->
                      <a href="driving_school_d.php">

                <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <div class="hexagon"><span> </span></div>
                         
                        <div class="icon"> <img src="https://img.icons8.com/external-others-pike-picture/50/000000/external-curriculum-recruitment-and-research-employee-others-pike-picture-16.png" /> </div>
                        <h5 id="font_style">Driving school</h5>
                        
                        <hr>

                </li>
            </a>

                

                <!--======= SERVICES 1 =========-->
                <a href="v_insurance_d.php">

            <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <div class="hexagon"><span> </span></div>
                        <div class="icon"> <img src="https://img.icons8.com/ios-filled/50/000000/steering-wheel.png" /> </div>
                        <h5 id="font_style">Vehicle Insurance</h5>
                        <hr>
                       

                </li></a>

                <!--======= SERVICES 3 =========-->
               
              <a href="v_loan_d.php"> 
                              <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <div class="hexagon"><span> </span></div>
                        <div class="icon"> <img src="https://img.icons8.com/external-jumpicon-glyph-ayub-irawan/32/000000/external-auto-pollution-jumpicon-glyph-jumpicon-glyph-ayub-irawan.png" /></i> </div>
                        <h5 id="font_style"> Vehicle Loan</h5>
                        <hr>

                </li>
            </a> 
        </div></br>
        <div class="container"> 
            <div class="row">
                 <li class="col-sm-6 col-md-2"></li>
                <!--======= SERVICES 4 =========-->
                 <a href="emission_d.php">

                <li class="col-sm-6 col-md-4">
                         <div class="inner">
                        <div class="hexagon"><span> </span></div>
                         
                        <div class="icon"> <img src="https://img.icons8.com/ios/50/000000/driver-license-card.png" /> </div>
                        <h5 id="font_style">Emission Analysis</h5>
                        <hr>
                    
                </li>
            </a>
                <a href="auto_c_d.php">
                 <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <div class="hexagon"><span> </span></div>
                          
                        <div class="icon"> <img src="https://img.icons8.com/external-jumpicon-glyph-ayub-irawan/32/000000/external-auto-pollution-jumpicon-glyph-jumpicon-glyph-ayub-irawan.png" /></i> </div>

                        <h5 id="font_style">Auto Consultancy</h5>

                        <hr>

                </li>
            </a>
            </ul>
        </div>
    </section>

   
    <!-- branche -->
     <section id="video" data-stellar-background-ratio="0.2">
        <div class="container text-center">
            <h1><span class="theme-blue">Our Branches</span></h1>
            <!-- <h3 class="theme-blue">Driving lessons and related services</h3> -->
    </section>
     <style type="text/css">
            #features li a:hover {
        /* background: #647382; */
        color: white;
        /* border-color: #647382; */
    }
    #features li a:hover {
    /* background: #647382; */
    color: white;
    /* border-color: #647382; */
}
    #features li a {
    /* background: #f8f8f8; */
    /* border: 1px solid #dee1e4; */
    display: inline-block;
    line-height: 35px;
    padding: 0 20px;
    border-radius: 4px;
    margin-top: 20px;
    -webkit-transition: 0.4s ease-in-out;
    -moz-transition: 0.4s ease-in-out;
    -ms-transition: 0.4s ease-in-out;
    -o-transition: 0.4s ease-in-out;
    transition: 0.4s ease-in-out;
}
    #features li a {
    /* background: #f8f8f8; */
    /* border: 1px solid #dee1e4; */
    display: inline-block;
    line-height: 35px;
    color: black;
    padding: 0 20px;
    border-radius: 4px;
    margin-top: 20px;
    -webkit-transition: 0.4s ease-in-out;
    -moz-transition: 0.4s ease-in-out;
    -ms-transition: 0.4s ease-in-out;
    -o-transition: 0.4s ease-in-out;
    transition: 0.4s ease-in-out;
}

#features li .inner {
    text-align: center;
    position: relative;
    border: 1px solid #dee1e4;
    padding: 30px 20px;
    padding-top: 70px;
    border-radius: 4px;
    -webkit-transition: 0.4s ease-in-out;
    -moz-transition: 0.4s ease-in-out;
    -ms-transition: 0.4s ease-in-out;
    -o-transition: 0.4s ease-in-out;
    transition: 0.4s ease-in-out;
        height: 100vh;
}
#features li:hover .inner {
    background: #307E56;
    border-color: #307E56;
}
#features li:hover .inner a {
   color: white;
}
#features li:hover .inner h5 {
   color: white;
}
        </style>

   <section id="features">
        <div class="container">

            <!--======= FEATURE =========-->
            <ul class="row" id="font_style">
                <!--======= SERVICES 1 =========-->
                <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88">Benaka Motor Driving School
                        Somwarpet, Karnataka 571236</a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto: bmds.spet@gmail.com">  bmds.spet@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918762282525"> +918762282525</a></h5>
                             <hr>
                              <h5> <i class="fa fa-whatsapp my-float"></i> <a href="https://api.whatsapp.com/send?phone=+918762282525&text=Hi, Benaka Moto Driving School"> +918762282525</a></h5>
                        <hr>

                         </br>
                         </br></br></br>
                       
                </li>

                <!--======= SERVICES 1 =========-->
            <li class="col-sm-6 col-md-4">
                    <div class="inner">
                       <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88">Benaka Motor Driving School
                                #3, B.C.J Complex, Opp. Co-Operative Bank,
                                Main Road, Shanivarasanthe - 571 235
                                Somwarapet Taluk, <!-- Phone - 08276-283535 --></a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto:bmds.santhe@gmail.com">  bmds.santhe@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918276 283535"> +918276-283535</a></h5>
                        <hr>
                        <h5> <i class="fa fa-whatsapp my-float"></i> <a href="https://api.whatsapp.com/send?phone=+919481283535&text=Hi, Benaka Moto Driving School"> +919481283535</a></h5>
                        <hr>
                        <p style="display:none">Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.</p>
</br>
                         </br></br></br>
                </li>

                <!--======= SERVICES 3 =========-->               
             <!-- <a href="service.php?status=ec"> -->
                <li class="col-sm-6 col-md-4">
                    <div class="inner">     
                         <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88"> Benaka Motor Driving School<br>
                                Near Asha Grinder, Manju Complex, Kushal Nagar, Hebbale</a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto:bmds.hebbale@gmail.com">bmds.hebbale@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918762982525"> +918762982525</a></h5>
                        <hr>
                         <h5> <i class="fa fa-whatsapp my-float"></i> <a href="https://api.whatsapp.com/send?phone=+918762982525&text=Hi,Benaka Moto Driving School"> +918762982525</a></h5>
                        <hr>

                </li><br>
            <!-- </a> -->
        </div>
        </ul>
        </div>

    </section>
    <section id="features">
        <div class="container">

            <!--======= FEATURE =========-->
            <ul class="row">
                <!--======= SERVICES 1 =========-->
                <li class="col-sm-6 col-md-4">
                    <div class="inner">
                        <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88"> Benaka Motor Driving School<br>
                                1st Floor, Srinidhi Complex, Near Bus Stand, Kodlipete, Somwarapet Taluk
                                Kodagu District 571 231, <!-- Phone: 08276 - 280505 --></a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto:bmds.kodlipet@gmail.com"> bmds.kodlipet@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918276 280505"> +918276-280505</a></h5>
                             <hr>
                              <h5> <i class="fa fa-whatsapp my-float"></i> <a href="https://api.whatsapp.com/send?phone=+919480280505&text=Hi, Benaka Moto Driving School"> +919480280505</a></h5>
                        <hr>

                         </br>
                         </br></br></br>
                       
                </li>

                <!--======= SERVICES 1 =========-->
            <li class="col-sm-6 col-md-4">
                    <div class="inner">
                       
                       <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88">Madapura:
                        Benaka Motor Driving School, PACS( VSSBN) Building, Madikeri Road, Madapura, Somwarpet Tq, Kodagu 571251</a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto:bmds.madapur@gmail.com">bmds.madapur@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918272-201010"> +918272-201010</a></h5>
                        <hr>
                         <h5> <i class="fa fa-whatsapp my-float"></i> <a href="https://api.whatsapp.com/send?phone=+918762271010&text=Hi,Benaka Moto Driving School"> +918762271010</a></h5><hr>
                       
                        <p style="display:none">Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.</p>

                </li>

                <!--======= SERVICES 3 =========-->               
             <!-- <a href="service.php?status=ec"> -->
                <li class="col-sm-6 col-md-4">
                 <!--    <div class="inner">
                        </a>
                         <h5> <i class="fa fa-map-marker "></i><a href="https://www.google.com/maps/dir//SH+91+Karnataka+571232/@12.5308098,75.9781609,13z/data=!4m5!4m4!1m0!1m2!1m1!1s0x3ba5128523c47427:0xb9f29ba13d89bf88"> Benaka Motor Driving School<br>
                                Near Asha Grinder, Manju Complex, Kushal Nagar, Hebbale</a></h5>
                        <hr>
                        <h5> <i class="fa fa-envelope"></i><a href="mailto:benakamotodrive@gmail.com"> benakamotodrive@gmail.com</a></h5><hr>
                        <h5> <i class="fa fa-phone"></i> <a href="tel:+918762982525"> +918762982525</a></h5>
                        <hr>

                </li></br> -->
                </br></br></br>
            <!-- </a> -->
        </div>
        </ul>
        </div>
        
    </section>
    <!-- end -->
    <?php
    include 'footer.php';
    ?>
    <style type="text/css">
        @media only screen and (max-width: 600px) {
         #features li .inner {
            text-align: center;
            position: relative;
            border: 1px solid #dee1e4;
            padding: 30px 20px;
            padding-top: 70px;
            margin: 10px;
            border-radius: 4px;
            -webkit-transition: 0.4s ease-in-out;
            -moz-transition: 0.4s ease-in-out;
            -ms-transition: 0.4s ease-in-out;
            -o-transition: 0.4s ease-in-out;
            transition: 0.4s ease-in-out;
                height: 115vh; 
         }
}
    </style>