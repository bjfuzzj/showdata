<?php
/**
 * Created by PhpStorm.
 * User: bjfuzzj
 * Date: 16/11/9
 * Time: 22:30
 */
function autoload_thornbird($classname) {
    $classname = strtolower($classname);
    //static $pre = '/data/wwwroot/pubsystem';
    static $classpath = array(
        'index' => '/data/wwwroot/pubsystem/index.php',
        'login' => '/data/wwwroot/pubsystem/login.php',
        'dbfaculty' => '/data/wwwroot/pubsystem/plib/db.php',
        'tool' => '/data/wwwroot/pubsystem/plib/tool.php',
        'page' => '/data/wwwroot/pubsystem/plib/page.php',
    );
    if (!empty($classpath[$classname])) {
        include($classpath[$classname]);
    }
}
spl_autoload_register('autoload_thornbird');
