<?php

namespace TomsSweip;

class TomsSweip
{
    protected $token = ""; // String Yes	API密钥
    protected $key = ""; // String Yes	API标识
    protected $service = ""; // String Yes	接口方法，参考接口方法列表

    protected $params = [];
    protected $pageSize = 20; // 每页数据长度，不传值表示查询所有
    protected $page = 1; // 当前页，不传值表示查询所有
    protected $url = "https://toms.sweip.com/default/svc/web-service";
    private $language = "zh_CN";

    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }



    /**
     * @param $key string 字段
     * @param $value object 值
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }


    /**
     * 发起API请求
     * @return array|mixed
     */
    public function PostSoapXML()
    {
        $params = "{}";
        if ($this->params) {
            $params = json_encode($this->params);
        }

        // 定义 SOAP 请求的 XML
        $body = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.example.org/Ec/">
    <SOAP-ENV:Body>
        <ns1:callService>
            <paramsJson>{$params}</paramsJson>
            <appToken>{$this->getToken()}</appToken>
            <appKey>{$this->getKey()}</appKey>
            <service>{$this->getService()}</service>
            <language>{$this->getLanguage()}</language>
        </ns1:callService>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_SSL_VERIFYPEER => false, // 禁用 SSL 证书验证
            ));

            $response = curl_exec($curl);
            if (!$response) {
                $error = curl_error($curl); // 获取 cURL 错误信息
                curl_close($curl); // 关闭 cURL 会话
                throw new \Exception("cURL Error: $error");
            }

            $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
            $jsonString = (string)$xml->xpath('//response')[0];

            // 将 JSON 字符串转换为 PHP 数组
            return json_decode($jsonString, true);
        } catch (\Exception $exception) {
            return ["Error" => $exception->getMessage()];
        }
    }
}
