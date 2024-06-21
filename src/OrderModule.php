<?php

namespace TomsSweip;

// 在 BasicData.php 文件顶部添加
require_once 'TomsSweip.php';

class OrderModule extends TomsSweip
{
    public function __construct($token, $key)
    {
        parent::__construct($token, $key);
    }

    /**
     * @param $codes array require 单号数组,如{"codes":["TFL1"," TFL2"]}
     * @return void
     */
    public function setCodes(array $codes)
    {
        $this->params["codes"] = $codes;
    }

    /**
     * @param $type int option 单号类型：1-运单号,2-客户订单号,3-跟踪号，不填默认三个单号都支持查询
     * @return void
     */
    public function setType(int $type)
    {
        $this->params["type"] = $type;
    }

    /**
     * @param $lang string option 轨迹内容语言：CN--中文，EN--英文，不传默认为为英文，没有英文时用中文填充
     * @return void
     */
    public function setLang(string $lang)
    {
        $this->params["lang"] = $lang;
    }

    /**
     * getCargoTrack() 轨迹查询
     *
     * $codes object require 单号数组,如{"codes":["TFL1"," TFL2"]}
     * $type int option 单号类型：1-运单号,2-客户订单号,3-跟踪号，不填默认三个单号都支持查询
     * $lang string option 轨迹内容语言：CN--中文，EN--英文，不传默认为为英文，没有英文时用中文填充
     *
     * 示例：{"codes":["QGAMEX18041600000033"]}
     *
     * response参数：
     * ask string 参考response公共参数
     * message string 参考response公共参数
     * Data object 数据内容(2维数组),参考参考Data说明
     * Error object 参考Error格式
     *
     * Data参数：
     * Code string 单号
     * Country_code string 目的国家代码
     * New_date string 最新的轨迹时间，格式为：YYYY-MM-DD HH:MM:SS
     * New_Comment string 最新的轨迹内容
     * Status string 轨迹状态,参考轨迹状态说明
     * CreateBy string 创建人
     *
     * @return array|mixed
     */
    public function getCargoTrack()
    {
        $this->setService("getCargoTrack");
        return $this->PostSoapXML();
    }
}