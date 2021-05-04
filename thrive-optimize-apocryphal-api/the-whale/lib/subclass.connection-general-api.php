<?php

	namespace apt\thewhale;
	
	abstract class connection_general_api extends connection_general{
		
		protected $api_url; //adress complete, including http or https
		protected $request_type; //post | get | put | etc
		protected $parameters; //array - the parameters we should send on the request
		protected $body_json; //boolean - if the parameters should be json_encoded before send
		protected $response_type; //json | xml | etc
		protected $response;
		
		protected function do_connection(){
			
			switch(strtolower($this->request_type)){
				
				case 'post':
					break;
				
				default:
					
					$request = curl_init();
					curl_setopt($request, CURLOPT_URL, $this->api_url."?".http_build_query($this->parameters));
					curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($request, CURLOPT_POST, 0);
					curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
					
					$response = curl_exec($request);
					
					$this->proccess_response($response);
			}
		}
		
		protected function proccess_response($response){
			
			switch(strtolower($this->response_type)){
				case "json":
				default:
					$this->response = json_decode($response);
			}
		}
		
		protected function get_response(){
			return $this->response;
		}
		
	}
	
?>