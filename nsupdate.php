<?php
/*	nsupdate.php - PHP script to update BIND DNS records using nsupdate(1)
	
	This script updates an IPv4 A record and an IPv6 AAAA record when polled. The IPv4 address is
	pulled from the REMOTE_ADDR environment variable, the IPv6 address is specified in the query
	(e.g. nsupdate.php?v6=::1).
	
	The script performs no updates if the IP addresses have not changed.
	
	Key values to replace: IPV4HOSTNAME, IPV6HOSTNAME, DOMAINNAME, BINDKEY
	
	Code licensed under the MIT License.
*/

$currentv4 = gethostbyname("IPV4HOSTNAME");
$currentv6rec = dns_get_record("IPV6HOSTNAME", DNS_AAAA);
$currentv6 = $currentv6rec[0]['ipv6'];

$submittedv4 = $_SERVER['REMOTE_ADDR'];
$submittedv6 = $_GET['v6'];

$stufftodo=false;

$nsupdatecommand = <<<EOF
server localhost
zone DOMAINNAME
EOF;

if($currentv4 != $submittedv4) {
  $stufftodo = true;
  print "IPV4HOSTNAME changing from $currentv4 to $submittedv4";
  $nsupdatecommand = $nsupdatecommand . "\nupdate delete IPV4HOSTNAME A";
  $nsupdatecommand = $nsupdatecommand . "\nupdate add IPV4HOSTNAME. 86400 A $submittedv4";
}

if($submittedv6 != $currentv6) {
  $stufftodo = true;
  print "IPV6HOSTNAME changing from $currentv6 to $submittedv6";
  $nsupdatecommand = $nsupdatecommand . "\nupdate delete IPV6HOSTNAME AAAA";
  $nsupdatecommand = $nsupdatecommand . "\nupdate add IPV6HOSTNAME. 86400 AAAA $submittedv6";
}

if(!$stufftodo) {
  return;
}

$nsupdatecommand = $nsupdatecommand . "\nsend\nexit\n";

$nsupdatecall = popen('/usr/bin/nsupdate -k /etc/bind/keys/BINDKEY.private -v', 'w');
fwrite($nsupdatecall, $nsupdatecommand);
$retval = pclose($nsupdatecall);
print "\nreturn value = " . $retval . "\n";
?>
