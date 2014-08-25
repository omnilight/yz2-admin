<?php

namespace yz\admin\helpers;


/**
 * Class SystemInfo
 * @author Eugine Terentev <eugine@terentev.net>
 * @url https://github.com/trntv/system-info
 * @package \yz\admin\helpers
 */
class SystemInfo
{
    /**
     * @return string
     */
    public static function getPhpVersion(){
        return phpversion();
    }

    /**
     * @return string
     */
    public static function getOS(){
        return php_uname('s r v');
    }

    /**
     * @return string
     */
    public static function getHostname(){
        return php_uname('n');
    }

    /**
     * @return string
     */
    public static function getArchitecture(){
        return php_uname('m');
    }

    /**
     * @return bool
     */
    public static function getIsWindows(){
        return strpos(strtolower(PHP_OS),'win') === 0;
    }

    /**
     * @return array|null
     */
    public static function getCpuCores(){
        return self::getCpuinfo('cpu cores');
    }

    /**
     * @return mixed
     */
    public static function getServerIP(){
        return $_SERVER['SERVER_ADDR'];
    }

    /**
     * @return string
     */
    public static function getExternalIP(){
        return @file_get_contents('http://ipecho.net/plain');
    }

    /**
     * @return mixed
     */
    public static function getServerSoftware(){
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * @return bool
     */
    public static function getIsNginx(){
        return strpos(strtolower(self::getServerSoftware()), 'nginx') !== false;
    }

    /**
     * @return bool
     */
    public static function getIsApache(){
        return strpos(strtolower(self::getServerSoftware()), 'apache') !== false;
    }

    /**
     * @param int $what
     * @return string
     */
    public static function getPhpInfo($what = -1){
        ob_start();
        phpinfo($what);
        return ob_get_clean();
    }

    /**
     * @return array
     */
    public static function getPHPDisabledFunctions(){
        return array_map('trim',explode(',',ini_get('disable_functions')));
    }

    /**
     * @param array $hosts
     * @param int $count
     * @return array
     */
    public static function getPing(array $hosts = null, $count = 2){
        if(!$hosts){
            $hosts = array("gnu.org", "github.com", "wikipedia.org");
        }
        $ping = [];
        for ($i = 0; $i < count($hosts); $i++) {
            $command = self::getIsWindows()
                ? 'ping' // todo: Windows
                : "/bin/ping -qc {$count} {$hosts[$i]} | awk -F/ '/^rtt/ { print $5 }'";
            $result = array();
            exec($command, $result);
            $ping[$hosts[$i]] = isset($result[0]) ? $result[0] : false;
        }
        return $ping;
    }

    /**
     * @param bool|int $key
     * @return mixed string|array
     */
    public static function getLoadAverage($key = false){
        $la = array_combine([1,5,15], sys_getloadavg());
        return ($key !== false && isset($la[$key])) ? $la[$key] : $la;
    }

    /**
     * @return mixed
     */
    public static function getDbType(){
        return \Yii::$app->db->driverName;
    }

    /**
     * @return string
     */
    public static function getDbVersion(){
        return \Yii::$app->db->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }
}