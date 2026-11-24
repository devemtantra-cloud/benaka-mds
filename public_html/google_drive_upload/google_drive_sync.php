<?php 
// Include Google drive api handler class 
class GoogleDriveApi { 
    const OAUTH2_TOKEN_URI = 'https://oauth2.googleapis.com/token'; 
    const DRIVE_FILE_UPLOAD_URI = 'https://www.googleapis.com/upload/drive/v3/files'; 
    const DRIVE_FILE_META_URI = 'https://www.googleapis.com/drive/v3/files/'; 
     
    function __construct($params = array()) { 
        if (count($params) > 0){ 
            $this->initialize($params);         
        } 
    } 
     
    function initialize($params = array()) { 
        if (count($params) > 0){ 
            foreach ($params as $key => $val){ 
                if (isset($this->$key)){ 
                    $this->$key = $val; 
                } 
            }         
        } 
    } 
     
    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) { 
        $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code'; 
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, self::OAUTH2_TOKEN_URI);         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
        curl_setopt($ch, CURLOPT_POST, 1);         
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);     
        $data = json_decode(curl_exec($ch), true); 
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
         
        if ($http_code != 200) { 
            $error_msg = 'Failed to receieve access token'; 
            if (curl_errno($ch)) { 
                $error_msg = curl_error($ch); 
            } 
            throw new Exception('Error '.$http_code.': '.$error_msg); 
        } 
             
        return $data; 
    } 
     
    public function UploadFileToDrive($access_token, $file_content, $mime_type) { 
        $apiURL = self::DRIVE_FILE_UPLOAD_URI . '?uploadType=media'; 
         
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, $apiURL);         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
        curl_setopt($ch, CURLOPT_POST, 1);         
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.$mime_type, 'Authorization: Bearer '. $access_token)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $file_content); 
        $data = json_decode(curl_exec($ch), true); 
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);         
         
        if ($http_code != 200) { 
            $error_msg = 'Failed to upload file to Google Drive'; 
            if (curl_errno($ch)) { 
                $error_msg = curl_error($ch); 
            } 
            throw new Exception('Error '.$http_code.': '.$error_msg); 
        } 
 
        return $data['id']; 
    } 
     
    public function UpdateFileMeta($access_token, $file_id, $file_meatadata) { 
        $apiURL = self::DRIVE_FILE_META_URI . $file_id; 
         
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, $apiURL);         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
        curl_setopt($ch, CURLOPT_POST, 1);         
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $access_token)); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($file_meatadata)); 
        $data = json_decode(curl_exec($ch), true); 
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);         
         
        if ($http_code != 200) { 
            $error_msg = 'Failed to update file metadata'; 
            if (curl_errno($ch)) { 
                $error_msg = curl_error($ch); 
            } 
            throw new Exception('Error '.$http_code.': '.$error_msg); 
        } 
 
        return $data; 
    } 
}  
     
require __DIR__ . '/dbConfig.php';
 
 
$statusMsg = ''; 
$status = 'danger'; 

    // Initialize Google Drive API class 
    $GoogleDriveApi = new GoogleDriveApi(); 
     
    // Get file reference ID from SESSION 
    // $file_id = $_SESSION['last_file_id']; 
 
    // if(!empty($file_id)){ 
         
    //     // Fetch file details from the database 
    //     $sqlQ = "SELECT * FROM drive_files WHERE id = ?"; 
    //     $stmt = $db->prepare($sqlQ);  
    //     $stmt->bind_param("i", $db_file_id); 
    //     $db_file_id = $file_id; 
    //     $stmt->execute(); 
    //     $result = $stmt->get_result(); 
    //     $fileData = $result->fetch_assoc(); 
         
       
            $file_name = 'json_rem.txt'; 
            $target_file = '../admin/app/'.$file_name; 
            $file_content = file_get_contents($target_file); 
            $mime_type = mime_content_type($target_file); 
             
            // Get the access token 
            if(!empty($_SESSION['google_access_token'])){ 
                $access_token = $_SESSION['google_access_token']; 
            }else{ 
                $data = $GoogleDriveApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
                $access_token = $data['access_token']; 
                $_SESSION['google_access_token'] = $access_token; 
            }  
                  
                    // Upload file to Google drive 
                    $drive_file_id = $GoogleDriveApi->UploadFileToDrive($access_token, $file_content, $mime_type); 
                     
                    if($drive_file_id){ 
                        $file_meta = array( 
                            'name' => basename($file_name) 
                        ); 
                        } 
                        // Update file metadata in Google drive 
                        $drive_file_meta = $GoogleDriveApi->UpdateFileMeta($access_token, $drive_file_id, $file_meta); 
     
    $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
     
    echo '<script>alert("uploaded Succesfully");
         window.location.href = "../admin/app/index.php";
         </script>';
    exit(); 

?>