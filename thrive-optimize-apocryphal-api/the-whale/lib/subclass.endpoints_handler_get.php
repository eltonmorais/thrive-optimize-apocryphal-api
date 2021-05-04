<?php

namespace apt\thewhale;

abstract class endpoints_handler_get extends endpoints_handler
{

    protected function add_endpoint()
    {
        register_rest_route($this->name_space . '/v1', "/$this->slug", array(
            'methods' => 'GET',
            'callback' => array($this, "callback"),
        ));
    }

    // abstract function handle_params();
}
