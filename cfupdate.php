<?php
/*	cfupdate.php - PHP script to update a CloudFlare dynamic DNS record
	
	This script updates an IPv4 A record when polled. The IPv4 address is pulled from the
	REMOTE_ADDR environment variable.
		
	The script performs no updates if the IP addresses have not changed.
	
	Key values to replace: CLOUDFLAREAPIKEY, CLOUDFLAREUSERID, IPV4HOSTNAME
	
	Code licensed under the MIT License.
*/

$cfapikey = 'CLOUDFLAREAPIKEY';
$cfuser = 'CLOUDFLAREUSERID';

$host = 'IPV4HOSTNAME';

$currentv4 = gethostbyname($host);

$submittedv4 = $_SERVER['REMOTE_ADDR'];

$nsupdateformat = 'curl -s https://www.cloudflare.com/api.html?a=DIUP\&hosts="%s"\&u="%s"\&tkn="%s"\&ip=%s';

$stufftodo=false;

if($currentv4 != $submittedv4) {
  $stufftodo = false;
  print sprintf("%s changing from %s to %s\n", $host, $currentv4, $submittedv4);
}

if(!$stufftodo) {
  return;
}

$nsupdatecommand = sprintf($nsupdateformat, $host, $cfuser, $cfapikey, $submittedv4);

print shell_exec($nsupdatecommand);
?>