<?php

/*
* System Status - for webservers and mailservers
*
* Main file for the system status function.
*
* (c) 2015 Rune Gulbrandsøy (http://ghostblog.be)
*
*
*/

include 'functions.php';

/*
* Configurations
*/

// Page refresh time in minutes. Set to 0 (zero) to disable.

	$refresh_time = 0;


// Domain and port to ping
	$domain = '';
	$port = '80';
	$ping_success = '';
	$ping_fail = '';




// Mailserver information
	$username = '';
	$password = '';
	$mailserver = '';
	$mail_success = '';
	$mail_fail = '';

// File modification check

	$remote_file_to_check = '';
  $time_zone_to_use = 'Europe/Oslo';

// System information

	$name_of_page = 'System Status';
	$meta_description = 'GulbrandsøyWEB tjenestestatus';

/*
* End of configurations
*/

/*
* Checks being done
*/

// Ping the webserver

$ping = ServerPing($domain,$port);

if ($ping == -1){
		$server_ping_class = 'danger';
	} else {
		$server_ping_class = 'success';
}

// Mailserver connection, ping and closing

$mailBox = ConnectMailServer($mailserver,$username,$password);
$mailStatus = PingMailServer($mailBox);
imap_close($mailBox);
if($mailStatus == 1){$mail_status_class = 'success';} else {$mail_status_class = 'danger';}

// Checking a file modification time and age

$status= FileTime($remote_file_to_check,$time_zone_to_use);
$age = FileAge($remote_file_to_check, $time_zone_to_use);

if ($status == 0){
		$file_time_class = 'success';
	} elseif ($status == 1) {
		$file_time_class = 'warning';
	} else {
		$file_time_class = 'danger';
}

/*
* End of checks
*/

/*
* The rest is just the HTML output.
*/

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description;?>">
    <meta name="author" content="Rune Gulbrandsøy / GulbrandsøyWEB">
    <meta http-equiv="Cache-control" content="no-cache">
    <?php if($refresh_time != 0) {$refresh_time=$refresh_time*60;header("refresh: $refresh_time;");}?>
    <title><?php echo $name_of_page;?></title>

    <!-- CSS files -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    
  </head>

  <body>

    <!-- Wrap all page content here -->
    <div id="wrap">
      <!-- Page content -->
      
      <!-- Begin menu -->
            <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
          <span class="navbar-brand"><button type="button" class="btn btn-link btn-xs" onclick="javascript:window.location.reload(true)"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button> <?php echo $name_of_page;?></span>
          </div>
          
        </div>
      </div>

    
      <!-- Begin page content -->
      <div class="container">
      
        
        <div id="status_banners">
      <!-- Output of alert and info boxes. Done with PHP -->  
        <?php 

        // Result of web server ping

        if ($ping == -1)
        echo '<div class="alert alert-'.$server_ping_class.'">'.$ping_fail.'</div>';
    	else echo '<div class="alert alert-'.$server_ping_class.'">'.$ping_success.'</div>';
    	
    	// Result of mailserver check

    	if ($mailStatus == 1) echo '<div class="alert alert-'.$mail_status_class.'">'.$mail_success.'</div>';
    	else echo '<div class="alert alert-'.$mail_status_class.'">'.$mail_fail.'</div>';

    	// Result of file time and age check

    // File exists and are between 0 and 5 minutes old.
      if ($status == 0) echo '<div class="alert alert-'.$file_time_class.'"><strong><em>orkan.be</em></strong> fungerer OK! Siste oppdatering for '.$age.' minutter siden!</div>';
        
		// File exists and are between 6 and 30 minutes old.
      if ($status == 1) echo '<div class="alert alert-'.$file_time_class.'"><strong><em>orkan.be</em></strong> fungerer. Siste oppdatering for '.$age.' minutter siden.</div>';
        
    // File exists and are between and are over 30 minutes old.
      if ($status == 2) echo '<div class="alert alert-'.$file_time_class.'"><strong><em>orkan.be</em></strong> kan være nede. Siste oppdatering er over '.$age.' minutter gammel.</div>';
        
 		// File does NOT exists. Check you settings and fileserver / webserver.
      if ($status == -1) echo '<div class="alert alert-'.$file_time_class.'">En alvorlig feil har oppstått!</div>';
        
        ?>

        <!-- End of output of alert and info boxes. --> 
        </div>

      </div>
    
    </div>

  </body>
</html>
<?php
?>