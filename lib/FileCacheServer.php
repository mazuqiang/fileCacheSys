<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/2
 * Time: 19:38
 */

include_once "FileCacheSystem.php";

class FileCacheServer implements FileCacheSystem
{

    private $path;

    private $data;

    private $key;

    private $rootDir;

    private $min = 0;

    private $hours = 0;

    private $day = 0;

    private $month = 0;

    private $always = 0;

    private $node = 0;

    private $nodeNameLength = 0;

    public function __construct($config, $params)
    {
        $cacheName = $params['cacheName'];
        unset($params['cacheName']);
        $this->data = $params;
        $this->path = $this->prasePath($cacheName);
        if (empty($this->path)) {
            throw new Exception('缓存不存在', -1);
        }
        $this->rootDir = $config['cacheDir'];
        $this->node = $config['node'];
        $this->nodeNameLength = $config['nodeNameLength'];

    }

    public function getKey()
    {
        return $this->key;
    }

    public function getPath()
    {
        $path = '';
        $key = $this->getKey();
        for ($i = 0; $i < $this->node; $i++) {
            $path .= '/' . substr($key, $i * $this->nodeNameLength, $this->nodeNameLength);
        }
        return $this->rootDir . $this->path . $path;
    }

    public function getFileName()
    {

        return $this->getPath() . "/" . $this->getKey();
    }

    /**
     * 参数是没有用的
     * @param null $name
     * @param int $timeOut
     * @return string
     */
    public function get($name = null, $timeOut = 600)
    {
        // TODO: Implement get() method.
        if ($this->timeOut()) {
            return file_get_contents($this->getFileName());
        }
        return '';
    }

    public static function mkdirs($dir)
    {
        if (!is_dir($dir)) {
            if (!mkdirs(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir, 0777)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 参数是没有用的
     * @param null $name
     * @param null $value
     * @param int $timeOut
     * @return bool|int|void
     */
    public function set($name = null, $value = null, $timeOut = 600)
    {
        // TODO: Implement set() method.
        self::mkdirs($this->getPath());
        return file_put_contents($this->getFileName(), json_encode($this->data));
    }

    /**
     * 参数是没有用的
     * @param null $name
     * @param int $timeOut
     * @return bool
     */
    public function exists($name = null, $timeOut = 600)
    {
        // TODO: Implement exists() method.
        return file_exists($this->getFileName());
    }

    /**
     * 参数是没有用的
     * @param null $name
     * @param int $timeOut
     * @return bool
     */
    public function del($name = null, $timeOut = 600)
    {
        // TODO: Implement del() method.
        if ($this->exists()) {
            unlink($this->getFileName());
        }
        return true;
    }

    /**
     * 缓存时间过期发挥false, 未过期返回true
     * @param $name
     * @param int $timeOut
     * @return bool
     */
    public function timeOut($name = null, $timeOut = 600)
    {
        // TODO: Implement timeOut() method.
        if ($this->exists()) {
            if ($this->always) {
                return true;
            } else {
                $cacheTime = $this->month * 43200 * 60 + $this->day * 1440 * 60 + $this->hours * 60 * 60 + $this->min * 60;
                $time = filemtime($this->getFileName());
                if ($cacheTime + $time < time()) {
                    $this->del();
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 解析成路径
     * @param $cacheName
     * @return string
     * @throws Exception
     */
    public function prasePath($cacheName)
    {
        $path = '';

        $cacheName = explode('|', $cacheName);
        if (empty($cacheName)) {
            return $path;
        }
        $pathArray = array(
            'min' => 0,
            'hours' => 0,
            'day' => 0,
            'month' => 0,
            'always' => 0,
            'name' => 0
        );
        foreach ($cacheName as $v) {
            $n = explode('_', $v);
            if (empty($n) || empty($n[1])) {
                continue;
            }
            $pathArray[$n[0]] = $n[1];
        }
        if (empty($pathArray['always'])) {
            $path = '/' . $pathArray['month'] . '/' . $pathArray['day'] . '/' . $pathArray['hours'] . '/' . $pathArray['min'];
        } else {
            $path = '/' . $pathArray['always'];
        }
        if (empty($pathArray['name'])) {
            throw new Exception('缓存名字不存在', -2);
        }

        $this->min = $pathArray['min'];
        $this->hours = $pathArray['hours'];
        $this->day = $pathArray['day'];
        $this->month = $pathArray['month'];
        $this->always = $pathArray['always'];

        $this->key = md5($pathArray['name']);
        return $path;
    }


}