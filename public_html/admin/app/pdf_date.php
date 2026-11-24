<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include "header.php"; 
include '../database.php';
	$tdate=$_GET['tdate'];
	$fdate=$_GET['fdate'];
	$service=$_GET['shift'];
	echo $jobb=$_GET['rpt'];
	$case_worker=$_GET['case'];
	
	error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<style>
td {
    border: 1px solid black;
}
tr#border {
    border: 1px solid black;
}
table#datatable1 {
    border: 1px solid black;
}
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}


h2.center {
    text-align: center;
}
hr{
	
    display: block;
    unicode-bidi: isolate;
    margin-block-start: 0.5em;
    margin-block-end: 0.5em;
    margin-inline-start: auto;
    margin-inline-end: auto;
    overflow: hidden;
    border-style: inset;
    border-width: 4px;
	webkit-text-decoration-color: black; /* Safari */  
  text-decoration-color: black;
}
span.att {
    font-weight: 600;
    font-size: 18px;
}
span.dep {
    font-size: 18px;
    font-weight: 600;
}
.split-para      { display:block;margin:10px;font-size: 18px;}
.split-para span { display:block;float:right;width:19%;margin-left:10px;}
</style>

<body  style="font-family: Arial, Helvetica, sans-serif;" onload= "window.print()">
	<div class="kt-mainpanel">
  <div class="kt-pagetitle">
    <h5>Report</h5>
 
  </div><!-- kt-pagetitle -->
	<div class="container">
		<h2 class="center"><?php echo $addf;?></h2>
	<center><span class="date" style="font-size:21px;"> <?php echo "From:&nbsp;".$fdate."&nbsp;TO:&nbsp;".$tdate;?></span></center>
	<div class="right">
		
	</div>
	<?php $datt =date("Y-m-d");
	?>
		<hr></hr>



<?php

//month=01&rtype=monthly&rpt=absent&year=2020

function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for (
        $currentDate = $startDate;
        $currentDate <= $endDate;
        $currentDate += (86400)
    ) {

        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}

$dates = getBetweenDates($fdate, $tdate);

?>

<?php
	foreach ($dates as $value)  

						{
									$count="1";
									$searchs="SELECT  `status` FROM `status`";
									$results = mysqli_query($con, $searchs);
								
										while ($rows = mysqli_fetch_array($results)) {
									$st=$rows[0];

									if($service == 'All' && $jobb =='All'){
										  $search="SELECT * FROM `history` WHERE `Job_data`='$st' and date(`Date`)='$value'";
									}

									else if($jobb == 'All' && $service != 'All'){
										  $search="SELECT * FROM `history` WHERE  sr_type='$service'  and `Job_data`='$st' and date(`Date`)='$value'";

									}
									else if($service === '' && $case_worker != 'All' && $jobb === 'All' )
									{
										$search="SELECT * FROM `history` WHERE  `sr_type`='$service' and `case_worker`='$case_worker' and `Job_data`='$st' and date(`Date`)='$value'";
									}
									else if($service != '' && $case_worker != '' && $jobb != '' )
									{
									 	echo $search="SELECT * FROM `history` WHERE `sr_type`='$service' and `case_worker`='$case_worker' and `Job_data`='$jobb' and date(`Date`)='$value'";
									}
									else{
					
										  $search="SELECT * FROM `history` WHERE `Job_data`='$jobb' and sr_type='$service'  and date(`Date`)='$value'";
									}
									$result = mysqli_query($con, $search);
									$rowcount=mysqli_num_rows($result);



							 
								if ($rowcount>0) 
								{
									


								?>
								<style type="text/css">
									th{
										border: 1px solid black;
									}
									.table tbody > tr > th {
    color: #343a40;
    font-weight: 500;
    border: 1px solid black;
}
								</style>
								
						 <table id="datatable1" class="table display responsive nowrap" border="1px solid ">
								<tr> 
									<th>SR NO.</th>
									<th>Name</th>
						            <th>Docment Number</th>
						            <th>Contact Number</th>
						            <th>Srvice Type</th>
						            <th>Case Worker</th>
						             <th>Exp date</th>
						            <th>Vehicle</th>
						            <th>job data</th>
						            <th>Remark</th>
								</tr>

								<?php
								echo "<span class='att'>Date:</span>".$value. "</br>";
 						
								
								
								echo "</br>";
								echo "<span class='dep'>Status:".$jobb."</span>";
								
						  		while ($row = mysqli_fetch_array($result)) 
						  		{
						  			?>
						  				<tr id="border" >
						  					<td><?php echo $count; ?></td>
						  					<td><?php echo $row['name']; ?></td>
									  		<td><?php echo $row['v_no'];?></td>
									  		<td><?php echo $row['w_number']; ?></td>
									  		<td><?php echo $row['sr_type']; ?></td>
									  		<td><?php echo $row['case_worker']; ?></td>
									  		<td><?php 
									  		$d1= $row['exp_date']; 
									  		echo $newDate = date("d-m-Y", strtotime($d1));
									  		?></td>
									  		
									  		<td><?php echo $row['v_name']; ?></td>
									  		<td><?php echo $row['Job_data']; ?></td>
									  		<td><?php echo $row['remark']; ?></td>
								  		</tr>

						  		<?php

						  		}
							}
															 }
							?></table> 

							<?php 
		}	
	
	?>

</body>
</html>


  <script>
    var $rows = $('#table tr');
    $('#search').keyup(function() {
      var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

      $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
      }).hide();
    });
  </script>
  <style type="text/css">


  </style>
