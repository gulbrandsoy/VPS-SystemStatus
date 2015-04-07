<?php
  
function ServerPing($domain, $port){
    $starttime = microtime(true);
    $file      = fsockopen ($domain, $port, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file) $status = -1;  // Site is down
    else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;

}
function retrieve_remote_file_time($url){




    $ch = curl_init($url);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
     curl_setopt($ch, CURLOPT_FILETIME, TRUE);
     //curl_setopt($ch, CURLINFO_HTTP_CODE, TRUE);
     

     $data = curl_exec($ch);
     $filetime = curl_getinfo($ch, CURLINFO_FILETIME);
     $filetime_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

     curl_close($ch);
     if($filetime_status == 200){$filetime = $filetime;} else {$filetime = -1;}
     
     return $filetime;
}
function FileAge($file_name){
  date_default_timezone_set('Europe/Oslo');
  $FileCreationTime = retrieve_remote_file_time($file_name);
  if($FileCreationTime != -1){
      $FileCreationTime = date('c',$FileCreationTime);
      $datetime1 = new DateTime('NOW');

      $datetime2 = new DateTime($FileCreationTime);
   
      $interval = $datetime1->diff($datetime2);
      $FileAge = $interval->format('%i');
  
      
  } else {
      $FileAge = -1;
      
  }
  return $FileAge;
}

function FileTime($file_name){

  date_default_timezone_set('Europe/Oslo');
  $FileCreationTime = retrieve_remote_file_time($file_name);
  
  if($FileCreationTime != -1){
      $FileCreationTime = date('c',$FileCreationTime);
      $datetime1 = new DateTime('NOW');

      $datetime2 = new DateTime($FileCreationTime);
   
      $interval = $datetime1->diff($datetime2);
      $FileAge = $interval->format('%i');
  
     if($FileAge <= 5) $status = 0; //ok Newer than 5 mins
     if($FileAge >= 6 && $FileAge <= 30) $status = 1; // Mellom 6 og 30 min
     if($FileAge >= 31) $status = 2; // Over 30 min
  
  
  } else {
      $status = -1;
  
  }
  
  return $status;
  
  
}

function ConnectMailServer($server,$user,$password){
  
$server = gethostbyname($server);
  
$mbox = imap_open ('{'.$server.':993/imap/ssl/novalidate-cert}', $user, $password);
return $mbox;
}

function PingMailServer($mailBox){
if (!imap_ping($mailBox)) {
    $imap_status = 0;

} else {$imap_status = 1;}
return $imap_status;
}

?>