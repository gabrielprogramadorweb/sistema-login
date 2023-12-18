<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

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
        // Llama al método report de la clase padre
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
        // Llama al método render de la clase padre
        return parent::render($request, $exception);
    }

    ////////// Adicionar ====>>>>>>>>>>>>>>
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            // Si la solicitud espera una respuesta JSON
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        $guard = data_get($exception->guards(), 0); // Reemplaza array_get con data_get
        switch ($guard) {
            case 'admin':
                $login = 'admin.login';
                break;
            case 'web':
                $login = 'login';
                break;
            default:
                $login = 'login';
        }

        // Redirige al usuario invitado (no autenticado) a la ruta de inicio de sesión correspondiente al guardia
        return redirect()->guest(route($login));
    }
}
