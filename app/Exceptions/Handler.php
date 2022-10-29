<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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


    public function render($request, Exception $e) {

        // echo '<pre>'; print_r($e);   die;
        
        // if route not found - showing customize error show file 
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
         
            return response(view('frontEnd.error_404'), 404);
        }

        /*if ($exception instanceof FatalErrorException) {
            //return response()->view('errors.custom', [], 500);
            //return response(view('frontEnd.error_404'), 404);
            //return response(view('frontEnd.dashboard'), 500);
            //return view('frontEnd.dashboard');
        }*/

        if ($e instanceof \Symfony\Component\Debug\Exception\FatalErrorException) { //echo 'OK'; die;
        
            $error = $e->getMessage();
            $path = $request->url();
    
            // echo '<pre>'; print_r($e);   die;

            return redirect('/bug-report?err='.$error.'&path='.$path);
        }
     
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
