<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Traits\ApiConnexion;

class ApiController extends Controller
{
    use ApiResponser;
    use ApiConnexion;
}