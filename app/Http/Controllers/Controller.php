<?php

namespace App\Http\Controllers;

use stdClass;

abstract class Controller
{
    protected stdClass $response;

    public function __construct()
    {
        $this->response = new stdClass();
    }
}
