<?php

namespace apt\thewhale;

class ab_tests extends endpoints_handler_get
{
    protected $slug = "ab-tests";
    protected $name_space = "tvo-apo-api";
    protected $_thrive_optimize_service;

    function handle_params()
    {
        $this->_session_init();
        $this->_initialize_thrive_service();

        if ($this->_have_specific_test_id()) {
            return $this->_get_specific_test_data();
        }

        return $this->_get_tests_list();
    }

    protected function _have_specific_test_id()
    {
        return $this->params['test_id'] != null ? true : false;
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

    protected function _get_tests_list()
    {
        $_return_data = $this->_thrive_optimize_service->tests_list_all($this->params['test_status']);
        
        if (empty($_return_data)) {
            http_response_code(404);
            $_response["message"] = "No AB Test found.";
            $_response["status"] = "error";
            echo json_encode($_response);
            die();
        }

        echo json_encode($_return_data);
    }

    protected function _get_specific_test_data()
    {
        $_return_data = $this->_thrive_optimize_service->tests_list_by_id($this->params['test_id']);

        if (empty($_return_data)) {
            http_response_code(404);
            $_response["message"] = "The test ID {$this->params['test_id']} was not found.";
            $_response["status"] = "error";
            echo json_encode($_response);
            die();
        }

        echo json_encode($_return_data);
    }
}
