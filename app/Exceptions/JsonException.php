<?php
namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

class JsonException
{

    private $exception;
    private $statusCode;

    public function __construct(\Throwable $exception, $statusCode)
    {
        $this->exception = $exception;
        $this->statusCode = $statusCode;
    }

    public function getResponse(array $headers = [], $options = 0)
    {
        $json = $this->getJson();
        return new JsonResponse($json, $this->statusCode, $headers, $options);
    }

    /**
     * @param array|null $data
     * @return array
     */
    public function getJson($data = null)
    {
        $data = [
            'data'    => $data,
            'toastrs' => [toastr('error', $this->exception->getMessage(), get_class($this->exception))],
        ];

        if (app()->environment('local', 'testing')) {
            $i = 1;
            $previous = $this->exception;
            do {
                $data['exception_previous_' . ($i++)] = [
                    'class'   => get_class($previous),
                    'message' => $previous->getMessage(),
                    'stack'   => $previous->getTrace(),
                ];
            } while (($previous = $previous->getPrevious()) !== null);
        }

        return $data;
    }
}
