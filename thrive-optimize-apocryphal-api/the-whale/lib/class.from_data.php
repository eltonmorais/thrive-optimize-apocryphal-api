<?php

namespace apt\thewhale;

abstract class from_data
{
    function __construct($_data)
    {
        foreach ($_data as $_key => $_param) {
            $_current_param = "_$_key";
            $this->$_current_param = $_param;
        }
    }

    public function get_param($param)
    {
        $_param_wanted = "_$param";
        if (property_exists($this, $_param_wanted)) {
            return $this->$_param_wanted;
        }

        return "";
    }

    public function get_nested_param($parent,$param){
        if (property_exists($this, $parent)) {
            $parent_data = $this->$parent;
        }

        if(isset($parent_data)){
            $parent_data_decoded = json_decode($parent_data);
            if(json_last_error() == JSON_ERROR_NONE){
                if(in_array($param,array_keys($parent_data_decoded))){
                    return $parent_data_decoded[$param];
                }
            }
        }

        return "";
    }
}
