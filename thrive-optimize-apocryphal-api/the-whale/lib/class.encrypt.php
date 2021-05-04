<?php

namespace apt\thewhale;

class encrypt{
	
	protected $secret_key;
	protected $secret_iv;
	protected $output;
	protected $encrypt_method;
	protected $key;
	protected $iv;
	
	function __construct(){
		
		$this->secret_key = get_site_url(get_current_blog_id());
		$this->secret_iv = get_site_url(get_current_blog_id());
		$this->output = false;
		$this->encrypt_method = "AES-128-CTR";
		$this->key = hash( 'sha128', $secret_key );
		$this->iv = substr( hash( 'sha128', $secret_iv ), 0, 16 );
	}
	
	public function encrypt($data){
		
		if(is_object($data) || is_array($data)){
			$string = serialize($data);
		}else{
			$string = $data;
		}
		
		return openssl_encrypt($string,$this->encrypt_method, $this->key, $this->output, $this->iv);
	}
	
	public function decrypt($data){
		
		echo "decryptor decryp<br />";
		echo "data: $data<br />";
		
		$return = openssl_decrypt($data,$this->encrypt_method, $this->key, $this->output, $this->iv);
		
		echo "return: $return<br />";
		echo "return: ".unserialize($return)."<br />";
		//if(is_serialized($return)){
			$return = unserialize($return);
		//}
		
		return $return;
	}
}

?>