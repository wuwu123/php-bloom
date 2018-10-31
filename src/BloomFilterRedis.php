<?php
/**
 * Created by PhpStorm.
 * User: wujie
 * Date: 2018/10/31
 * Time: 14:37
 */

namespace bloom;


class BloomFilterRedis
{
    /**
     * 需要使用一个方法来定义bucket的名字
     */
    protected $bucket;

    protected $hashFunction;

    /**
     * BloomFilterRedis constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (!$this->bucket || !$this->hashFunction) {
            throw new \Exception("需要定义bucket和hashFunction", 1);
        }
        if (!isset($config["redis"]) || !is_array($config["redis"])) {
            throw new \Exception("redis 配置缺失", 1);
        }
        $this->Hash = new BloomFilterHash;
        $this->Redis = (new RedisClient($config["redis"]))->getClient(); //假设这里你已经连接好了
    }

    /**
     * 添加到集合中
     */
    public function add($string)
    {
        return $this->changeBit($string, 1);
    }

    public function del($string)
    {
        return $this->changeBit($string, 0);
    }

    public function changeBit($string, $num = 0)
    {
        $pipe = $this->Redis->multi();
        foreach ($this->hashFunction as $function) {
            $hash = $this->Hash->$function($string);
            $pipe->setBit($this->bucket, $hash, $num);
        }
        return $pipe->exec();
    }

    /**
     * 查询是否存在, 存在的一定会存在, 不存在有一定几率会误判
     */
    public function exists($string)
    {
        $pipe = $this->Redis->multi();
        $len = strlen($string);
        foreach ($this->hashFunction as $function) {
            $hash = $this->Hash->$function($string, $len);
            $pipe = $pipe->getBit($this->bucket, $hash);
        }
        $res = $pipe->exec();
        foreach ($res as $bit) {
            if ($bit == 0) {
                return false;
            }
        }
        return true;
    }
}