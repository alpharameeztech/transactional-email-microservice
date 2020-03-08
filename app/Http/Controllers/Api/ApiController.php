<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

class ApiController extends Controller
{

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return ApiController
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    public function respondInterError($message = 'Internal Error!')
    {
        return $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'statusCode' => $this->getStatusCode()
            ],
        ]);
    }

    public function log($data)
    {
        \Log::info($data);
    }

    protected function respondCreated($message = 'Resource created successfully'){

        return $this->respond([
            'data' => [
                'message' => $message,
                'statusCode' => $this->getStatusCode()
            ],
        ]);
    }
}
