<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
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
    public function render($request, Exception $exception) {
      /*
        This modification comes from:
        https://stackoverflow.com/questions/29479409/redirect-to-homepage-if-route-doesnt-exist-in-laravel-5

        The idea is to redirect a '404-event' to the root page
      */

      // original code had only the return line below
      // return parent::render($request, $exception);

      if ($this->isHttpException($exception)) {
        switch ($exception->getStatusCode()) {
         // not found
          case 404:
            return redirect()->route('entranceroute'); //->guest('home');
            break;

         // internal error
          case '500':
            return redirect()->route('entranceroute'); //->guest('home');
            break;

          default:
            return $this->renderHttpException($exception);
            break;
        } // ends switch/cases

      } // ends if that continues an 'else'
      else {

        // original code had only the return line below
        return parent::render($request, $exception);

      } // ends else of if
    } // ends render()

} // ends class Handler extends ExceptionHandler
