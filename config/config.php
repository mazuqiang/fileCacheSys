<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/3
 * Time: 11:12
 */
$dri = dirname(dirname(__FILE__));
return array(
    //缓存系统域名和端口
    'host' => "fc.kaixinwan.com",
    'prot' => 80,


    //缓存系统的缓存目录
    'cacheDir' => $dri . '/cache',

    //缓存节点
    'node' => '2',

    //节点名字的长度
    'nodeNameLength' => '4'
);