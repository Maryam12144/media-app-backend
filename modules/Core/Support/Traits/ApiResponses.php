<?php 

namespace Modules\Core\Support\Traits;

/**
 * Class ApiResponses
 * @package Modules\Core\Support\Traits
 */
trait ApiResponses
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @param mixed $statusCode
     * @return ApiResponses
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondDataUpdated($message = 'Your Data Was Updated Successfully')
    {
        return $this->setStatusCode(202)->respondWithData([
            'message' => $message
        ]);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondDataCreated($message = 'Your Data Was Created Successfully')
    {
        return $this->setStatusCode(201)->respondWithData([
            'message' => $message
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondRequestAccepted($data)
    {
        return $this->setStatusCode(202)->respondWithData($data);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWidthDataFound($data)
    {
        return $this->setStatusCode(302)->respondWithData($data);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest($message = 'Bad Request!')
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnauthorized($message = 'Unauthorized!')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'message' => $message,
            'status_code' => $this->getStatusCode()
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     * @internal param $message
     */
    public function respondWithData($data)
    {
        return $this->respond($data);
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}