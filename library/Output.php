<?php

class Output
{
    public static function successOutput($array = array())
    {
        $outputModule = Config::read('Framework.OutputModule');
        self::initModule($outputModule);
        if (!is_callable($outputModule, 'successOutput') && $array != null) {
            self::defaultErrorOutput('EXECUTE_OUTPUT_FAIL', '执行输出模块[' . $outputModule . ']失败,模型中没有实现successOutput()');
        }
        $outputModule::successOutput(Common::convertArray($array));
    }

    public static function errorOutput(string $errorCode, string $errorMsg, int $httpStatus = 400): void
    {
        $outputModule = Config::read('Framework.OutputModule');
        self::initModule($outputModule);
        if (!is_callable($outputModule, 'errorOutput')) {
            self::defaultErrorOutput('EXECUTE_OUTPUT_FAIL', '执行输出模块[' . $outputModule . ']失败,模型中没有实现errorOutput()');
        }
        $outputModule::errorOutput($errorCode,  $errorMsg,  $httpStatus);
    }

    public static function defaultErrorOutput(string $errorCode, string $errorMsg)
    {
        header("HTTP/1.1 500");
        exit('[code] ' . $errorCode . PHP_EOL . '[msg] ' . $errorMsg);
    }

    private static function initModule($moduleName)
    {
        $moduleFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . $moduleName . '.PHP';
        if (!is_file($moduleFile)) {
            $downloadUrl = 'compress.zlib://' . Config::read('Framework.ServiceUrl') . '/module/' . $moduleName . '.msphp';
            $result = file_get_contents($downloadUrl);
            if (!$result) {
                Output::defaultErrorOutput('DOWNLOAD_MODULE_FAILE', '模块库中不存在此模块');
            }
            if (!file_put_contents($moduleFile, $result)) {
                Output::defaultErrorOutput('WRITE_MODULE_FAILE', '写入模板失败,请检查是否拥有写入权限[' . $moduleFile . ']');
            }
        }
        require_once $moduleFile;
    }
}
