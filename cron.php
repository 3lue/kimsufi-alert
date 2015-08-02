<?php
/*

	Author:		DatN3xus
	URL: 		https://github.com/DatN3xus
			https://n3xus.de
	Contact:	pun1a@4players.de

	Title: 		kimsufi-alert
	Description:	Sends an email, if a specific server is available at kimsufi.com

*/

$server_reference = "";
$mailTo = "";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ws.ovh.com/dedicated/r2/ws.dispatcher/getAvailability2?callback=Request.JSONP.request_map.request_0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$web = curl_exec($ch);
$web = trim(substr($web, strpos($web, '(')),'();');
curl_close($ch);

$web_cache = json_decode($web, 1);
$server_available = false;
$zoneName = array();
foreach($web_cache["answer"]["availability"] as $key => $value) {
	if($value["reference"] == $server_reference) {
                foreach($value["metaZones"] as $zone) {
                        if($zone["availability"] != "unknown") {
                                $server_available = true;
                                $zoneName[] = $zone["zone"];
                        }
                }

		foreach($value["zones"] as $zone) {
                        if($zone["availability"] != "unknown") {
                                $server_available = true;
				$zoneName[] = $zone["zone"];
                        }
                }
	}
}

if($server_available) {
	mail($mailTo, "Kimsufi-Alert!", "Hi User!\n The Kimsufi-Server '" . $server_reference . "' is available now!\n It's available at following location(s): " . implode(", ", $zoneName) . "!\n\nGreetings DatN3xus\nhttps://github.com/DatN3xus/kimsufi-alert");
}
?>
