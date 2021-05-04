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
        echo json_encode($this->_thrive_optimize_service->get_tests($this->params['test_status']));
    }

    protected function _get_specific_test_data()
    {
        echo json_encode($this->_thrive_optimize_service->get_test_by_id($this->params['test_id']));
    }
}
