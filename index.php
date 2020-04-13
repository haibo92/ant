<?php

require_once('vendor/autoload.php');

$module = str_replace('/' . readEnv('ENTRY_PASSWORD') . '/', '', $_SERVER['PATH_INFO'], $result);

if ($result) {

    if ($module == '') {
        exit('欢迎访问antPHP~');
    }

    $moduleClass = useModule($module, array_merge(Common::convertArray(file_get_contents("php://input")), $_REQUEST));

    is_callable([$module, 'main']) ? successOutput($module::main()) :
        errorOutput('VERIFY_MODE_FAIL', '验证模块类型失败,该模块[' . $module . ']没有实现入口函数main()');
}
