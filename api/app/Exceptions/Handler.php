<?php

namespace App\Exceptions;

use App\Helpers\Helper;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (Throwable $e) {
            return $this->handleException($e);
        });
    }

    /**
     * Check and Handle somes Exception
     *
     * @param Throwable $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleException(Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            if ($exception->getPrevious() instanceof ModelNotFoundException) {
                return Helper::apiErrorResponse('Data Not Found', Response::HTTP_NOT_FOUND);
            }
            return Helper::apiErrorResponse('Endpoint Not Found', Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ValidationException) {
            return Helper::apiErrorResponse('Given Data is Invalid', Response::HTTP_UNPROCESSABLE_ENTITY, $this->transformErrors($exception));
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return Helper::apiErrorResponse('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return Helper::apiErrorResponse('Access Denied', Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return Helper::apiErrorResponse('Unauthenticated', Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof AuthorizationException) {
            return Helper::apiErrorResponse('Unauthenticated', Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return Helper::apiErrorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        return Helper::apiErrorResponse(
            app()->isProduction() ? 'Internal Server Error' : $exception->getMessage(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Undocumented function
     *
     * @param ValidationException $exception
     *
     * @return Array
     */
    private function transformErrors(ValidationException $exception)
    {
        $errors = [];
        if (is_array($exception->errors())) {
            foreach ($exception->errors() as $field => $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message[0],
                ];
            }
        }

        return $errors;
    }
}
