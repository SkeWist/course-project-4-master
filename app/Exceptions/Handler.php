<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use mysql_xdevapi\Exception;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) { // Параметр 'exception' вместо 'e'
            //
        });
    }

    /**
     * Регистрируем кастомную обработку исключений
     */
    public function render($request, Throwable $exception)
    {
        // Обработка ошибок валидации
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Ошибка валидации данных.',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Ошибка при модели (например, модель не найдена)
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Ресурс не найден.',
            ], 404);
        }

        // Ошибка доступа
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => 'У вас нет доступа к этому ресурсу.',
            ], 403);
        }

        // Ошибка в базе данных (например, уникальность поля)
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return response()->json([
                'message' => 'Произошла ошибка при выполнении запроса.',
                'error' => $exception->getMessage(),
            ], 500);
        }

        // Обработка других исключений
        return parent::render($request, $exception);
    }
}
