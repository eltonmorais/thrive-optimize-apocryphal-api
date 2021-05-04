<?php

namespace apt\thewhale;

class thrive_optimize_apocryphal_api extends the_whale_framework
{

	protected $_ab_tests_endpoint;
	protected $_conversion_endpoint;

	function plugin()
	{
		add_action('rest_api_init', array($this, 'init_endpoints'));
	}

	function init_endpoints()
	{
		$this->_ab_tests_endpoint = new ab_tests();
		$this->_conversion_endpoint = new conversion();
	}
}
