<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;

/**
 * Base API Controller.
 */
class APIController extends Controller
{
    /**
     * default success code.
     *
     * @var int
     */
    protected $successCode = 200;

    /**
     * get the success code.
     *
     * @return successcode
     */
    public function getsuccessCode()
    {
        return $this->successCode;
    }

    /**
     * set the success code.
     *
     * @param [type] $successCode [description]
     *
     * @return successcode
     */
    public function setsuccessCode($successCode)
    {
        $this->successCode = $successCode;

        return $this;
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getsuccessCode(), $headers);
    }

    /**
     * respond with pagincation.
     *
     * @param Paginator $items
     * @param array     $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination($items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $items->total(),
                'total_pages' => ceil($items->total() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit' => $items->perPage(),
            ],
        ]);

        return $this->respond($data);
    }

    /**
     * Respond Created.
     *
     * @param string $message
     * @param array|null $item
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($message, $item=null)
    {
        return $this->setsuccessCode(201)->respond([
            'success'   =>  true,
            'message'   =>  $message,
            'data'      => $item ?? null,
        ]);
    }

    /**
     * Respond Success.
     *
     * @param string $message
     * @param array|null $item
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($result, $message = '')
    {
        /*$data = [
            'success'    =>  true,
            'message'   =>  $message,
        ];

        if($item) {
            $data['data'] = $item;
        }*/

        $response = [
            'code'      => 200,
            'success'   => true,
            'response'  => $result,
            'message'   => $message,
        ];

        return $this->respond($response);
    }

    /**
     * respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        //dd("ddd");
        return $this->respond([
            'success'    =>  false,
            'message'   => $message,
        ]);
        
    }

    /**
     * responsd not found.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setsuccessCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error')
    {
        return $this->setsuccessCode(500)->respondWithError($message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setsuccessCode(401)->respondWithError($message);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->setsuccessCode(403)->respondWithError($message);
    }

    /**
     * Respond with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithNoContent($message = 'No content found')
    {
        //return response()->json(['message' => 'Yup. This request succeeded.'], 200);
       return $this->setsuccessCode(203)->respondWithError($message);
    }

    /**Note this function is same as the below function but instead of responding with error below function returns error json
     * Throw Validation.
     *
     * @param string $message
     *
     * @return mix
     */
    public function throwValidation($message)
    {
        return $this->setsuccessCode(422)
            ->respondWithError($message);
    }
}