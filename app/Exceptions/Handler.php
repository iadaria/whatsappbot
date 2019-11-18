<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
//'Symfony\Component\HttpKernel\Exception\HttpExceptionInterface

class Handler extends ExceptionHandler
{
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($this->isHttpException($exception))
        {
            if ($exception instanceof MethodNotAllowedHttpException) {
                return redirect('/');
            }
            switch ($exception->getStatusCode()) 
                {
                // not found
                //case 405:
                case 404:
                return redirect()->guest('home');
                break;

                // internal error
                case '500':
                return redirect()->guest('home');
                break;

                default:
                    return $this->renderHttpException($exception);
            }
        }
        else
        {
                return parent::render($request, $exception);
        }
    }
}
