<?php     
// Include database configuration file 
require 'dbConfig.php';

$statusMsg = $valErr = ''; 
$status = 'danger'; 
 
// If the form is submitted 
     
    // Validate form input fields 
  
     
    // Check whether user inputs are empty 
 
        $targetDir = "../../app"; 
        $fileName = "json_rem.txt"; 
        $targetFilePath = $targetDir . $fileName; 
         
        // Upload file to local server 
        
             
            // Insert data into the database 
            $sqlQ = "INSERT INTO drive_files (file_name,created) VALUES (?,NOW())"; 
            $stmt = $db->prepare($sqlQ); 
            $stmt->bind_param("s", $db_file_name); 
            $db_file_name = $fileName; 
            $insert = $stmt->execute(); 
                $file_id = $stmt->insert_id; 
                 
                // Store DB reference ID of file in SESSION 
                $_SESSION['last_file_id'] = $file_id; 
                 
                header("Location: $googleOauthURL"); 
                exit(); 

 
$_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
 
//header("Location: index.php"); 
exit(); 
?>