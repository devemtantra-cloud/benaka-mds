<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php

if (isset($_POST['btn_rpt'])) 
    {

        $fdate=$_POST['fdate'];
        $tdate=$_POST['tdate'];
        $shift_name=$_POST['service_type'];
        $jobdata=$_POST['jobdata'];
        $case_worker=$_POST['case_worker'];
        
        
       

        if ($fdate!="" && $tdate!="") 
        {
            header("location:pdf_date.php?fdate=$fdate&tdate=$tdate&rtype=$sel_rtype&rpt=$jobdata&shift=$shift_name&case=$case_worker");
        }
        else{
            echo "<script>alert('Please select Valied Data');</script>";
        }
        
    }


?>