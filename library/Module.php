<?php

class Module
{
    private static $payload = array();

    /**
     * 引用模块
     * @author mahaibo <mahaibo@hongbang.js.cn>
     * @param string $moduleName
     * @param array $payload
     * @return object
     */
    public static function use(string $moduleName, array $payload = array()): object
    {
        self::$payload = $payload;

        $moduleFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . $moduleName . '.PHP';
        is_file($moduleFile) ? $isDownload = false : self::download($moduleName, $moduleFile) and $isDownload = true;
        require_once $moduleFile;
        if (!class_exists($moduleName)) {
            Output::errorOutput('VERIFY_CLASS_FAIL', '验证模块类失败,该模块[' . $moduleName . ']未实现同名类');
        }

        $isDownload and self::firstLoad($moduleName);

        return new $moduleName();
    }

    public static function getPayload(string $key = ''): array
    {
        if ($key == '') {
            return self::$payload;
        }
        return self::$payload[$key] ?? array();
    }

    /**
     * 模块首次载入/下载加载模块
     * @author mahaibo <mahaibo@hongbang.js.cn>
     * @param string $moduleName 模块名称
     * @return void
     */
    private static function firstLoad(string $moduleName): void
    {
        //载入数据库依赖信息
        $dbDepend = $moduleName::$dbDepend ?? array();
        $dataBaseModule = Config::read('Framework.DataBaseModule');
        $dataBaseModule !== $moduleName && $dbDepend != null
            and self::use($dataBaseModule)->syncDataBase($dbDepend) or Output::errorOutput('SYNC_DATABASE_FAIL', '同步数据库结构失败');

        //模块初始化操作
        is_callable([$moduleName, 'init']) and self::use($moduleName)::init();
    }

    /**
     * 下载模块
     * @author mahaibo <mahaibo@hongbang.js.cn>
     * @param string $moduleName 下载模块名称
     * @param string $moduleFile 模块存储位置
     * @return void
     */
    private static function download(string $moduleName, string $moduleFile): void
    {
        $downloadUrl = 'compress.zlib://' . Config::read('Framework.ServiceUrl') . '/module/' . $moduleName . '.msphp';
        $result = file_get_contents($downloadUrl);
        if (!$result) {
            Output::errorOutput('DOWNLOAD_MODULE_FAIL', '官方模块库中不存在此模块');
        }
        if (!file_put_contents($moduleFile, $result)) {
            Output::errorOutput('WRITE_MODULE_FAIL', '写入模块失败,请检查是否拥有写入权限[' . $moduleFile . ']');
        }
    }
}
