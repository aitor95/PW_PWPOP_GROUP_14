<?php

namespace PwPop\Controller;

use PwPop\Model\Services\DBConnection;
use PwPop\View\Renderer;

class LoginController{

    private $renderer;
    private $service;

    public function __construct(DBConnection $service)
    {
        //$this->renderer = $renderer;
        $this->service = $service;
    }


}
