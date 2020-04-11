<?php

class Config
{

    //系统配置文件不允许在Module中修改
    private static $systemConfig = ['Framework', 'ModuleVersions'];

    private static $config = array();

    public static function read(string $configName)
    {
        $keyArray = explode('.', $configName);
        $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . array_shift($keyArray) . '.ENV';
        if (!is_file($filePath)) {
            Output::defaultErrorOutput('READ_CONFIG_FAIL', '读取配置文件失败,请检查配置文件[' . $filePath . ']是否存在');
        }
        $value = parse_ini_string(str_replace('#', ';', file_get_contents($filePath)));
        foreach ($keyArray as $key) {
            $value = $value[$key];
        }
        return $value;
    }
}
