<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser{

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
            'status'    => 'Success', 
            'code'      => $code,
			'message'   => $message, 
			'data'      => $data
		], $code);
	}

	protected function errorResponse($message = null, $code)
	{
		return response()->json([
            'status'    =>'Error',
            'code'      => $code,
			'message'   => $message,
			'data'      => null
		], $code);
	}

}