<?php
class CloudFlareAPI{	
	private $api;
	private $zone_id;		
	public function __construct($mail,$api_key)
	{
		$api = [ 			
			"X-Auth-Email: $mail",
			"X-Auth-Key: $api_key",
			'Content-Type: application/json'
		];
        $this->api = $api;
    }	
	public function setZone($domain_name)
	{
		$this->zone_id = $this -> getZoneID($domain_name);
	}
	public function getZoneID($domain_name) {
		/* SENDING RESPONSE */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones?name=$domain_name");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		/* RETURN */
		return $response['result'][0]['id'];
	}

	public function getInfoDNS($name) {	
		/* SENDING RESPONSE */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$this->zone_id."/dns_records?name=$name");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		$return = [
			"id" => $response['result'][0]['id'],
			"type" => $response['result'][0]['type'],
			"name" => $response['result'][0]['name'],
			"data" => $response['result'][0]['data'],
			"content" => $response['result'][0]['content'],
			"proxied" => $response['result'][0]['proxied'],
			"ttl" => $response['result'][0]['ttl']
		];	
		/* RETURN */
		return $return;
	}
	public function renameRecord($old_name,$new_name){
		$info = $this -> getInfoDNS($old_name);
		$info['data']['name'] = $new_name; //for srv records
		$update = $this -> updateDNSrecord($info['id'],$info['type'],$new_name,$info['content'],$info['data'],$info['ttl'],$info['proxied'],$this->zone_id);
		return $update;	
	}
	public function listDNSrecords() {	
		/* SENDING RESPONSE */	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$this->zone_id."/dns_records/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		$return = [];		
		if($response['success'] == true){	
			/* RETURN */
			for($i = 0; $i < count($response); $i++) {
				$return[$i] = [
					"id" => $response['result'][$i]['id'],
					"type" => $response['result'][$i]['type'],
					"name" => $response['result'][$i]['name'],
					"proxied" => $response['result'][$i]['proxied'],
					"ttl" => $response['result'][$i]['ttl']
				];				
			}
			return $return;
		}	
		else{	
		return false;
		}
	}
	public function addDNSrecord($type,$name,$content,$ttl = 1,$cloudflare_proxy = false) {	
		/* PARSING RESPONSE */
		$ch = curl_init();		
		$payload = json_encode(  array( "type"=> $type,"name" => $name, "content" => $content, "ttl" => $ttl, "proxied" => $cloudflare_proxy ));
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$this->zone_id."/dns_records/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		print_r($response);
		/* RETURN */
		if(!empty($response['success']))
		return true;
		else
		return false;
	}
	public function updateDNSrecord($old_name,$type,$name,$content,$data = null,$ttl = 1,$cloudflare_proxy = false)
	{	
		/* PARSING RESPONSE */
		$info = $this -> getInfoDNS($old_name);	
		$ch = curl_init(); 		
		$payload = json_encode( array( "type"=> $type,"name" => $name, "content" => $content,"data" => $data, "ttl" => $ttl, "proxied" => $cloudflare_proxy ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$this->zone_id."/dns_records/".$info['id']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);		
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		/* RETURN */
		if($response['success'] == true)
		return true;
		else
		return false;
	}
	public function deleteDNSrecord($name){	
		/* PARSING RESPONSE */	
		$info = $this -> getInfoDNS($name);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$this->zone_id."/dns_records/".$info['id']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this -> api);
		$content  = curl_exec($ch);
		curl_close($ch);
		/* PARSING RESPONSE */
		$response = json_decode($content,true);
		
		/* RETURN */
		if($response['success'] == true)
		return true;
		else
		return false;
	}
}
?>
