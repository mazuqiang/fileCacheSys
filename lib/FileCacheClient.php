<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/2
 * Time: 19:38
 * 粒度最小支持是分钟，不支持秒
 */
include_once "FileCacheSystem.php";

class FileCacheClient implements FileCacheSystem
{

    private $host = null;

    private $prot = 80;

    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->prot = $config['prot'];
    }


    public function get($name, $timeOut = 600)
    {
        $url = $this->getHost() . '/get';
        $cacheName = self::creatCacheName($name, $timeOut);
        $result = $this->urlGet($url . '?cacheName=' . $cacheName);
        return $result;
    }


    public function set($name, $value, $timeOut = 600)
    {
        $url = $this->getHost() . '/set';
        $cacheName = self::creatCacheName($name, $timeOut);
        if (is_string($value)) {
            $value = 'cacheName=' . $cacheName . $value;
        } else {
            $value['cacheName'] = $cacheName;
            $value = http_build_query($value);
        }
        var_dump($url . '?' . $value);
        exit();
        $result = $this->urlGet($url . '?' . $value);
        return $result;
    }

    public function del($name, $timeOut = 600)
    {
        // TODO: Implement del() method.
        $url = $this->getHost() . '/del';
        $cacheName = self::creatCacheName($name, $timeOut);
        $result = $this->urlGet($url . '?cacheName=' . $cacheName);
        return $result;
    }

    public function exists($name, $timeOut = 600)
    {
        // TODO: Implement exists() method.
        $url = $this->getHost() . '/exists';
        $cacheName = self::creatCacheName($name, $timeOut);
        $result = $this->urlGet($url . '?cacheName=' . $cacheName);
        return $result;
    }

    public function timeOut($name, $timeOut = 600)
    {
        // TODO: Implement timeOut() method.
        $url = $this->getHost() . '/timeOut';
        $cacheName = self::creatCacheName($name, $timeOut);
        $result = $this->urlGet($url . '?cacheName=' . $cacheName);
        return $result;
    }

    public function getHost()
    {
        return 'http://' . $this->host . ":" . $this->prot;

    }


    public function urlGet($url)
    {
        $ch = curl_init();
        $timeout = 1000;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }


    public static function creatCacheName($name, $timeOut = 600)
    {
        $min = 0;
        $hours = 0;
        $day = 0;
        $month = 0;
        $always = 0;
        if ($timeOut == -1 || $timeOut >= 518400) {
            $always = 12;
        } else {
            $month = intval($timeOut / 30 / 24 / 60);
            $day = intval(($timeOut - $month * 30 * 24 * 60) / 24 / 60);
            $hours = intval(($timeOut - $month * 30 * 24 * 60 - $day * 24 * 60) / 60);
            $min = $timeOut - $month * 30 * 24 * 60 - $day * 24 * 60 - $hours * 60;
        }
        $path = array(
            'min_' . $min,
            'hours_' . $hours,
            'day_' . $day,
            'month_' . $month,
            'always_' . $always,
            'name_' . $name
        );

        return implode('|', $path);
    }

}