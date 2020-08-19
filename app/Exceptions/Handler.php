<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;




use App\Traits\ApiResponser;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    /*
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
    */

    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }


    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->errorResponse('The specified record cannot be found', 404);
        }
        
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('The specified method for the request is invalid', 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL cannot be found', 404);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);            
        }

        return $this->errorResponse('Unexpected Exception. Try later', 500);

    }



/*
    public function render($request, Throwable $exception)
    {

        return parent::render($request, $exception);

        //dd($exception);
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }
    
        return $retval;
    }


    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);
    
        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }
    
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }
    
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }
    
        return $this->customApiResponse($exception);
    }


    private function customApiResponse00000($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }
    
        $response = [];
    
        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }
    
        // if (config('app.debug')) {
        //     $response['trace'] = $exception->getTrace();
        //     $response['code'] = $exception->getCode();
        // }


    
        $response['status'] = $statusCode;
    
        return response()->json($response, $statusCode);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['message'] = 'Accès à la ressource refusé'; //'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Accès à la ressource refusé'; //'Forbidden';
                break;
            case 404:
                $response['message'] = 'Document non trouvé'; //'Not Found';
                break;
            case 405:
                $response['message'] = 'Méthode Non Autorisée'; //'Method Not Allowed';
                break;
            case 422:
                $response['message'] = 'Les données fournies sont invalide';//$exception->original['message']; // 'Les données fournies sont invalide';
                $response['errors'] = $exception->original['errors'];
                break;
                
            case 105:
                $response['message'] = 'Prblème d\'accès au réseau, veillez vérifier votre connexion';
                break;
            case 106:
                $response['message'] = 'Prblème d\'accès au réseau, veillez vérifier votre connexion';
                break;
            case 511:
                $response['message'] = 'Prblème d\'accès au réseau, veillez vérifier votre connexion';
                break;

            default:
                $response['message'] = ($statusCode == 500) ? 'Internal server error' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
            //$response['debugggg'] = config('app.debug');
        }

        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }
*/


}
