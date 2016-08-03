# fileCacheSys
文件缓存系统

FileCacheClient 客户端 
FileCacheServer 服务端 

api:
    get($name, $timeOut)
    name： 缓存名字
    time： 缓存时间
    

    set($name, $value, $timeOut)
    name： 缓存名字
    value：缓存值
    time： 缓存时间
    
    
    name和time会组成一个缓存路径，服务器通过该路径判断缓存是否过期。
    
    配置文件的node和nodeNameLength
    是用来创建多层目录，减轻单个目录的中的缓存文件数量
    
    
扩展：
    通过配置nginx，可以客户端直接获取缓存文件。可以放在不同的机器上做读写分离。
    也可以通过文件名做负载均衡。怎么玩就看自己了
    
    
缺点：
    缓存目录过深，删文件会变得很复杂。先通过路径计算出缓存时间，然后获取缓存文件的创建时间，然后判断是否超时，最后删除。
    希望有大神能提供解决这个问题的思路。
    
    
最后：
   QQ： 844596330 了缺  备注一下来自github 有兴趣的一起研究编程
    
    
    
    
    
    
    
