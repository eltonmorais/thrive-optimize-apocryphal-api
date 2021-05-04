<?php

namespace apt\thewhale;

class zapier{
	
	protected $url;
	protected $data_to_send;
	
	function set_data($new_data){
		$this->data_to_send = $new_data;
	}
	
	function set_url($new_url){
		$this->url = $new_url;
	}
	
	function send(){
	
		$curl = curl_init();

		$jsonEncodedData = json_encode($this->data_to_send);
		$opts = array(
			CURLOPT_URL             => $this->url,
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_CUSTOMREQUEST   => 'POST',
			CURLOPT_POST            => 1,
			CURLOPT_POSTFIELDS      => $jsonEncodedData,
			CURLOPT_HTTPHEADER  => array('Content-Type: application/json','Content-Length: ' . strlen($jsonEncodedData))                                                                       
		);

		// Set curl options
		curl_setopt_array($curl, $opts);

		// Get the results
		$result = curl_exec($curl);

		// Close resource
		curl_close($curl);

	}
}

?>