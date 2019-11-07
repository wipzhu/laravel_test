<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/11/5
 * Time: 15:17
 */

define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__) . DS);
defined('APP_PATH') or define('APP_PATH', ROOT_PATH . 'app' . DS);
defined('TRAIT_PATH') or define('TRAIT_PATH', APP_PATH . 'Traits' . DS);
//defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
defined('STORAGE_PATH') or define('STORAGE_PATH', ROOT_PATH . 'storage' . DS);
defined('LOG_PATH') or define('LOG_PATH', STORAGE_PATH . 'logs' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', STORAGE_PATH . 'cache' . DS);
defined('TEMP_PATH') or define('TEMP_PATH', STORAGE_PATH . 'temp' . DS);
defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录
defined('CONF_EXT') or define('CONF_EXT', EXT); // 配置文件后缀
defined('ENV_PREFIX') or define('ENV_PREFIX', 'PHP_'); // 环境变量的配置前缀

// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);
