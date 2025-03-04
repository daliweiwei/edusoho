<?php

namespace ESCloud\SDK\Exception;

use ESCloud\SDK\HttpClient\Response;
use ESCloud\SDK;

class ResponseException extends SDKException
{
    protected $response;

    protected $responseData;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->responseData = SDK\json_decode($response->getBody(), true);

        $errorCode = (int) $this->get('code', -1);
        $errorMessage = $this->get('message', 'Unknown error');

        parent::__construct($errorMessage, $errorCode);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getResponseData()
    {
        return $this->responseData;
    }

    public function getRawResponse()
    {
        return $this->response->getBody();
    }

    public function getHttpResponseCode()
    {
        return $this->response->getHttpResponseCode();
    }

    public function getDebugTrace()
    {
        return $this->get('trace', array());
    }

    private function get($key, $default = null)
    {
        if (isset($this->responseData['error'][$key])) {
            return $this->responseData['error'][$key];
        }

        if (isset($this->responseData[$key])) {
            return $this->responseData[$key];
        }

        return $default;
    }
}
