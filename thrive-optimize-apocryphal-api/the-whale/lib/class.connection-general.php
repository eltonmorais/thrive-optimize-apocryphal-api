<?php

	namespace apt\thewhale;
	
	abstract class connection_general{
		
		function __construct(){
			
			$this->get_settings();
		}
		
		static function get_conn(){
			return new self();
		}
		
		abstract protected function get_settings();
		abstract protected function do_connection();
	}
?>