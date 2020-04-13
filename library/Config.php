<?php

class Config
{
    public static function read(string $configName)
    {
        $keyArray = explode('.', $configName);
        $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . array_shift($keyArray) . '.json';
        $configContent = $GLOBALS['config_' . $filePath];
        if (!is_file($filePath)) {
            Output::defaultErrorOutput('READ_CONFIG_FAIL', '读取配置文件失败,请检查配置文件[' . $filePath . ']是否存在');
        }
        if ($configContent === null) {
            $configContent = Common::convertArray(file_get_contents($filePath));
            $GLOBALS['config_' . $filePath] = $configContent;
        }
        foreach ($keyArray as $key) {
            $configContent = $configContent[$key];
        }
        return $configContent;
    }

    public static function write(string $configName, $content)
    {
        $keyArray = explode('.', $configName);
        $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . array_shift($keyArray) . '.json';
        $contentArr = array();
        if (is_file($filePath)) {
            $contentArr = Common::convertArray(file_get_contents($filePath));
        }
        $keyArray = array_reverse($keyArray);
        $lastKey = '';
        $newContent = array();
        foreach ($keyArray as $key) {
            $newContent[$key] = $content;
            unset($newContent[$lastKey]);
            $content = $newContent;
            $lastKey = $key;
        }
        if (!count($keyArray)) {
            $contentArr = array();
        }
        file_put_contents($filePath, json_encode(array_merge($contentArr, $content), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public static function readEnv(string $configName)
    {
        $envContent = $GLOBALS['env_content'];
        $keyArray = explode('.', $configName);
        $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($filePath)) {
            Output::defaultErrorOutput('READ_CONFIG_FAIL', '读取配置文件失败,请检查配置文件[' . $filePath . ']是否存在');
        }
        if ($envContent === null) {
            $envContent = parse_ini_string(str_replace('#', ';', file_get_contents($filePath)));
            $GLOBALS['env_content'] = $envContent;
        }
        foreach ($keyArray as $key) {
            $envContent = $envContent[$key] ?? null;
        }
        return $envContent;
    }
}
