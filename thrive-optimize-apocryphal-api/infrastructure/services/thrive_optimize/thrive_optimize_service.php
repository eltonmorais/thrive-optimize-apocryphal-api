<?php

namespace apt\thewhale;

class thrive_optimize_service
{

    protected $_thive_instance;
    protected $_tests;
    // protected $_post;
    // protected $_goal_pages;
    protected $_current_test_id;
    // protected $_variation_pages;
    // protected $_is_goal_page = 0;
    // protected $_is_variation_page = 0;
    // protected $_is_ab_page = 0;
    protected $_current_test_data;

    function __construct()
    {
        if (class_exists("Thrive_AB_Test_Manager")) {
            $this->_thive_instance = new \Thrive_AB_Test_Manager();
        } else {
            wp_die("Thrive Optimize is not installed or not activated in your WP.");
        }

        // $this->_post = $_post_id ? get_post($_post_id) : get_post();

        // $this->_post = get_post();
        // $this->tests_get();
        // $this->proccess_tests_data();
    }

    function get_tests($status = null)
    {
        $status_filter = array();
        if ($status == "completed" || $status == "running") {
            $status_filter['status'] = $status;
        }

        $_tests = $this->_thive_instance->get_tests($status_filter, 'object');

        foreach ($_tests as $_test) {

            $_test_data['test_data'] = $_test->get_data();

            $_ab_page = new \Thrive_AB_Page(get_post($_test_data['test_data']['page_id']));
            $_test_data['variations'] = $_ab_page->get_variations();

            $this->_tests[] = $_test_data;
        }

        return $this->_tests;
    }

    function get_test_by_id($id)
    {
        $this->get_tests();
        foreach ($this->_tests as $_test) {
            if ($_test["test_data"]["id"] == $id) {
                return $_test;
            }
        }

        http_response_code(404);
        $_response["message"] = "The test ID $id was not found.";
        $_response["status"] = "error";
        echo json_encode($_response);
        die();
    }

    protected function _set_current_test_id()
    {
        if (in_array($this->_post->ID, array_keys($this->_goal_pages))) {
            $this->_is_ab_page = 1;
            $this->_is_goal_page = 1;
            $this->_current_test_id = $this->_goal_pages[get_the_ID()];
        }

        if (in_array($this->_post->ID, array_keys($this->_variation_pages))) {
            $this->_is_ab_page = 1;
            $this->_is_variation_page = 1;
            $this->_current_test_id = $this->_variation_pages[get_the_ID()];
        }
    }

    protected function _set_current_test_data()
    {

        $this->_set_current_test_id();

        if (!$this->_is_ab_page) {
            return;
        }

        foreach ($this->_tests as $_test) {
            $_test_data = $_test["test_data"]->get_data();
            if ($_test_data['id'] == $this->_current_test_id) {
                $_test_data_found = $_test->get_data();
                break;
            }
        }

        $this->_current_test_data =  isset($_test_data_found) ? $_test_data_found : [];
    }

    public function get_current_test_id()
    {
        if (isset($this->_current_test_id)) {
            return $this->_current_test_id;
        }

        $this->_set_current_test_data();
        return $this->_current_test_id;
    }

    public function get_current_test_data()
    {
        if (isset($this->_current_test_data)) {
            return $this->_current_test_data;
        }

        $this->_set_current_test_data();
        return $this->_current_test_data;
    }

    protected function _set_goal_page_custom_revenue($_goal_page, $_value)
    {
        $_goal_page['revenue'] = $_value;
        return $_goal_page;
    }

    protected function _validate_conversion_data($_test_id, $_variation_id)
    {
        $this->_current_test_data = $this->get_test_by_id($_test_id);
        if (!$this->is_variation_page($_variation_id)) {
            http_response_code(404);
            $_response["message"] = "The post ID $_variation_id is not a valid variation for test $_test_id.";
            $_response["status"] = "error";
            echo json_encode($_response);
            die();
        }
    }

    public function is_variation_page($_variation_id)
    {
        foreach ($this->_current_test_data['variations'] as $variation) {
            if ($_variation_id == $variation["ID"]) {
                return true;
            }
        }

        return false;
    }

    public function conversion_add($_test_id, $_variation_id, $_value)
    {
        $this->_validate_conversion_data($_test_id, $_variation_id);

        $_test = $this->get_test_by_id($_test_id);
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

        echo json_encode(
            array(
                "message" => "A conversion was successfully sent to Thrive Optimize",
                "status" => "success",
                "conversion" => $conversion_data
            )
        );
    }
}
