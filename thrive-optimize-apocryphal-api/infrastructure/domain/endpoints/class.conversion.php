<?php

namespace apt\thewhale;

class conversion extends endpoints_handler_post
{
    protected $slug = "conversion";
    protected $name_space = "tvo-apo-api";
    protected $_thrive_optimize_service;
    protected $_test_id;
    protected $_page_id;
    protected $_variation_id;
    protected $_goal_page_id;
    protected $_value;

    function handle_params()
    {
        if (intval($this->params['test_id']) <= 0) {
            echo json_encode(array("status" => "error", "message" => "test_id not found."));
            return;
        }

        if (intval($this->params['variation_id']) <= 0) {
            echo json_encode(array("status" => "error", "message" => "variation_id not found."));
            return;
        }

        if (doubleval($this->params['value']) <= 0) {
            echo json_encode(array("status" => "error", "message" => "value not found."));
            return;
        }

        $this->_test_id = $this->params['test_id'];
        $this->_variation_id = $this->params['variation_id'];
        $this->_value = $this->params['value'];

        $this->_session_init();
        $this->_initialize_thrive_service();
        $this->_conversion_add();
    }

    protected function _session_init()
    {
        if (!session_id()) {
            session_start();
        }
    }

    protected function _initialize_thrive_service()
    {
        $this->_thrive_optimize_service = new thrive_optimize_service();
    }

    protected function _conversion_add()
    {
        $result = $this->_thrive_optimize_service->conversion_add(
            $this->_test_id,
            $this->_variation_id,
            $this->_value
        );

        http_response_code($result["status"]);
        echo json_encode($result);
        die();
    }
}
