<?php

function convertArray($variate, string $delimiter = ',')
{
    return Common::convertArray($variate, $delimiter);
}

/**
 * 成功输出
 * @param array|object|string|bool $result 输出数据,会自动转为Output模组指定类型
 * @return void
 */
function successOutput($result = array())
{
    Output::successOutput($result);
}

/**
 * 错误输出
 * @param string $errorCode 错误码,数字错误码需转换String
 * @param string $errorMsg 错误内容，错误提示
 * @param integer $httpStatus http状态码
 * @return void
 */
function errorOutput(string $errorCode, string $errorMsg, int $httpStatus = 400)
{
    Output::errorOutput($errorCode, $errorMsg, $httpStatus);
}

function useModule(string $moduleName, array $payload = array())
{
    return Module::use($moduleName, $payload);
}

function getPayload(string $key = '')
{
    return Module::getPayload($key);
}

/**
 * 读取配置文件
 * @param string $configName 配置文件名称 
 * 例 Framework.OutputModule 可直接 Framework配置文件中的OutputModule值
 * 如值为数组则支持多级取值,以'.'连接
 * @return void
 */
function readConfig(string $configName)
{
    return Config::read($configName);
}

function readEnv(string $configName)
{
    return Config::readEnv($configName);
}
