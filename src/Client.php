<?php

namespace yii\Swoft\JsonRpc;

use yii\Swoft\JsonRpc\Exception;
use yii\helpers\Json;

class Client {

    const RPC_EOL = "\r\n\r\n";

    private $host;
    private $class;
    private $version;
    private $ext;

    public function __construct($host = null, $class = null, $version = '1.0', $ext = [])
    {
        $this->host = $host;
        $this->class = $class;
        $this->version = $version;
        $this->ext = $ext;
    }

    public function __call($method, $param)
    {
        $fp = stream_socket_client($this->host, $errno, $errstr);
        if (!$fp) {
            throw new Exception("stream_socket_client failed errno={$errno} errstr={$errstr}", Exception::INTERNAL_ERROR);
        }

        $request = [
            "jsonrpc" => '2.0',
            "method" => sprintf("%s::%s::%s", $this->version, $this->class, $method),
            'params' => $param,
            'id' => '',
            'ext' => $this->ext,
        ];

        $data = Json::encode($request) . self::RPC_EOL;
        fwrite($fp, $data);

        $result = '';
        while (!feof($fp)) {
            $tmp = stream_socket_recvfrom($fp, 1024);

            if ($pos = strpos($tmp, self::RPC_EOL)) {
                $result .= substr($tmp, 0, $pos);
                break;
            } else {
                $result .= $tmp;
            }
        }

        fclose($fp);
        $response = Json::decode($result, true);

        if ($response === null) {
            throw new Exception('JSON cannot be decoded', Exception::INTERNAL_ERROR);
        } else if (array_key_exists('error', $response)) {
            throw new Exception($response['error']['message'], $response['error']['code']);
        } else if (!array_key_exists('result', $response)) {
            throw new Exception('JSON-RPC response is invalid', Exception::INTERNAL_ERROR);
        }

        return $response['result'];
    }
}
