<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/2
 * Time: 19:37
 */
Interface FileCacheSystem
{

    public function exists($name, $timeOut = 600);


    public function get($name, $timeOut = 600);


    public function set($name, $value, $timeOut = 600);


    public function del($name, $timeOut = 600);


    public function timeOut($name, $timeOut = 600);


}