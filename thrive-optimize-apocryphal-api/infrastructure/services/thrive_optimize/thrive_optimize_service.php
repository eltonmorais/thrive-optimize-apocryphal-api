<?php

namespace apt\thewhale;

class thrive_optimize_service
{
    protected $_thrive_instance;
    protected $tests;
    protected $current_test;

    function __construct()
    {
        $this->_initialize();
    }

    function _initialize()
    {
        try {
            $this->_thrive_instance = new \Thrive_AB_Test_Manager();
            $this->_set_tests();
            $this->_set_current_test();
        } catch (\Throwable $th) {
            trigger_error("Thrive AB Test Manager not available. Check if Thrive Optimize plugin is installed.", E_WARNING);
        }
    }

    function _set_tests()
    {
        $_tests_running = $this->_thrive_instance->get_tests(array("status" => "running"), 'object');
        $_tests_completed = $this->_thrive_instance->get_tests(array("status" => "completed"), 'object');
        $_tests_all = array_merge($_tests_running, $_tests_completed);

        foreach ($_tests_all as $_test) {

            $_test_data['test_data'] = $_test->get_data();
            $_ab_page = new \Thrive_AB_Page(get_post($_test_data['test_data']['page_id']));
            $_test_data['variations'] = $_ab_page->get_variations();

            $this->tests[] = $_test_data;
        }
    }

    function _set_current_test()
    {
        if (is_page()) {
            $this->current_test = $this->tests_list_by_id(get_the_ID());
        }
    }

    function tests_list_by_id($id)
    {
        foreach ($this->tests as $_test) {
            if ($_test["test_data"]["id"] == $id) {
                return $_test;
            }
        }
    }

    function tests_list_all($status = null)
    {
        if ($status == null) {
            return $this->tests;
        }

        $_tests_filtered = [];

        foreach ($this->tests as $_test) {
            if ($_test["test_data"]["status"] == $status) {
                $_tests_filtered[] = $_test;
            }
        }

        return $_tests_filtered;
    }

    public static function get_current_test_id()
    {
        $_instance = new thrive_optimize_service();
        return $_instance->current_test["test_data"]["id"];
    }

    public static function get_current_test_title()
    {
        $_instance = new thrive_optimize_service();
        return $_instance->current_test["test_data"]["title"];
    }

    public static function is_ab_test()
    {
        $_instance = new thrive_optimize_service();
        return $_instance->_is_variation_page(get_the_ID());
    }

    protected function _set_goal_page_custom_revenue($_goal_page, $_value)
    {
        $_goal_page['revenue'] = $_value;
        return $_goal_page;
    }

    protected function _validate_conversion_data($_test_id, $_variation_id)
    {
        $this->current_test = $this->tests_list_by_id($_test_id);

        if (!$this->_is_variation_page($_variation_id)) {
            $_response["status"] = 404;
            $_response["message"] = "The post ID $_variation_id is not a valid variation for test $_test_id.";
            return $_response;
        }
    }

    protected function _is_variation_page($_variation_id)
    {
        foreach ($this->current_test['variations'] as $variation) {
            if ($_variation_id == $variation["ID"]) {
                return true;
            }
        }

        return false;
    }

    public function conversion_add($_test_id, $_variation_id, $_value)
    {
        $_validation_result = $this->_validate_conversion_data($_test_id, $_variation_id);

        if (!empty($_validation_result)) {
            return $_validation_result;
        }

        $_test = $this->tests_list_by_id($_test_id);
        $_page_id = $_test["test_data"]["page_id"];
        $_goal_page = $_test['test_data']['goal_pages'][array_key_first($_test['test_data']['goal_pages'])];
        $_goal_page = $this->_set_goal_page_custom_revenue($_goal_page, $_value);

        $conversion_data = array(
            "test_id" => $_test_id,
            "page_id" => $_page_id,
            "variation_id" => $_variation_id,
            "goal_page" => $_goal_page
        );

        \Thrive_AB_Event_Manager::do_conversion(
            $_test_id,
            $_page_id,
            $_variation_id,
            $_goal_page
        );

        $_conversion_result = array(
            "message" => "A conversion was successfully sent to Thrive Optimize",
            "status" => 201,
            "conversion" => $conversion_data
        );

        return $_conversion_result;
    }
}
