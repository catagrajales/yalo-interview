<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //HTTP status codes
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_NO_CONTENT = 204;
    const STATUS_NOT_MODIFIED = 304;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_PAYMENT_REQUIRED = 402;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_UNPROCESSABLE_ENTITY = 422;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;
    
    //Response for all api calls
    protected $_response = [];
}
