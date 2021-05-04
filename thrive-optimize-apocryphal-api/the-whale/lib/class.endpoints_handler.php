<?php

namespace apt\thewhale;

abstract class endpoints_handler
{

    protected $slug;
    protected $name_space;
    protected $params;
    protected $request;

    function __construct()
    {
        $this->add_endpoint();
    }

    abstract protected function add_endpoint();

    function callback($request)
    {
        $this->request = $request;
        $this->params = $this->request->get_params();
        $this->handle_params();
    }

    abstract function handle_params();
}
