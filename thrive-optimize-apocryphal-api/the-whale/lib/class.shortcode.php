<?php

namespace apt\thewhale;

abstract class shortcode
{

    protected $_slug;

    function __construct()
    {
        add_shortcode($this->_slug, array($this, "_do_shortcode"));
    }

    abstract function _do_shortcode($data);

}