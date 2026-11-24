<?php
// Public page: customers open it from the WhatsApp link (feedback.php?no=<whatsapp number>).
include "../database.php";

function cut($s, $len)
{
    // Column sizes in `feeback`: name/branch/phone 22, comment 222.
    return function_exists('mb_substr') ? mb_substr($s, 0, $len) : substr($s, 0, $len);
}

$phone = isset($_GET['no']) ? trim($_GET['no']) : '';
$client_id = '';
$no = '';
$c_name = '';
$bnameb = '';

if ($phone !== '') {
    $stmt = mysqli_prepare($con, "SELECT c.`id`, c.`name`, c.`w_number`, b.`branch_name` FROM `client` c LEFT JOIN `branch` b ON b.`branch_id` = c.`branch_id` WHERE c.`w_number` = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $phone);
    mysqli_stmt_execute($stmt);
    $row_b = mysqli_fetch_row(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($row_b) {
        list($client_id, $c_name, $no, $bnameb) = [(string) $row_b[0], (string) $row_b[1], (string) $row_b[2], (string) $row_b[3]];
    }
}

if (isset($_POST["submit"])) {
    $experience = isset($_POST['experience']) ? $_POST['experience'] : '';
    $comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';

    if ($client_id === '') {
        $msg = 'This feedback link is not valid. Please use the link sent to you on WhatsApp.';
    } elseif (!in_array($experience, ['bad', 'average', 'good'], true)) {
        $msg = 'Please choose a rating.';
    } else {
        // Name, branch and phone come from our records, not from the form.
        $name = cut($c_name, 22);
        $comment = cut($comments, 222);
        $branch = cut($bnameb, 22);
        $ph = cut($no, 22);
        $stmt = mysqli_prepare($con, "INSERT INTO `feeback` (`name`, `comment`, `rate`, `c_id`, `Branch name`, `Phone`) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssssss', $name, $comment, $experience, $client_id, $branch, $ph);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if ($ok) {
            echo '<script>alert("Thank you for your feedback!");
     window.location.href = "https://benakamds.in";
     </script>';
            exit;
        }
        $msg = 'Sorry, your feedback could not be saved. Please try again.';
    }
    echo '<script>alert(' . json_encode($msg) . ');</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: aliceblue;"> 
<center><h2> Service Feedback </h2></center>
    <div class="container py-5">

<div class="imagebg"></div>
<div class="row " style="margin-top: 50px">
    <div class="col-md-6 col-md-offset-3 form-container">
        <p>
            Please provide your feedback below:
        </p>
        <form role="form" method="post" id="reused_form">
            <div class="row">
                <div class="col-sm-12 form-group">
                <label>How do you rate your overall experience?</label>
                <p>
                    <label class="radio-inline">
                    <input type="radio" name="experience" id="radio_experience" value="bad" >
                    Bad
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="experience" id="radio_experience" value="average" >
                    Average
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="experience" id="radio_experience" value="good" >
                    Good
                    </label>
                </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label for="comments">
                        Comments:</label>
                    <textarea class="form-control" type="textarea" name="comments" id="comments" placeholder="Your Comments" maxlength="222" rows="7"></textarea>
                </div>
            </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="name">
                       Branch name</label>
                    <input type="text" class="form-control" id="Id" name="bname" value="<?php echo htmlspecialchars($bnameb); ?>" readonly>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="email">
                        Customer Name</label>
                    <input type="text" class="form-control" id="Name" name="c_name"  value="<?php echo htmlspecialchars($c_name); ?>" readonly>
                </div>
            </div>
           
</br>
                        <div class="row">
                <div class="col-sm-12 form-group">
                    <button type="submit" name="submit" class="btn btn-lg btn-warning btn-block" >Post </button>
                </div>
            </div>

        </form>
        <div id="success_message" style="width:100%; height:100%; display:none; ">
            <h3>Posted your feedback successfully!</h3>
        </div>
        <div id="error_message"
                style="width:100%; height:100%; display:none; ">
                    <h3>Error</h3>
                    Sorry there was an error sending your form.

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<style type="text/css">
    @media (min-width: 1400px){
.container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
    max-width: 1320px;
    left: 23%;
    position: relative;
}
}
</style>
