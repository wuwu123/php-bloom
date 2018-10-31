<?php
/**
 * Created by PhpStorm.
 * User: wujie
 * Date: 2018/10/31
 * Time: 14:42
 */

namespace bloom;


class RedisClient
{
    const MAX_RETRY = 3;
    const MAX_RETRY_WAIT = 3;

    private $hostname;
    private $port;
    private $password;
    private $select = 0;

    /**
     * @param mixed $hostname
     * @desc
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     * @desc
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     * @desc
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @param mixed $password
     * @desc
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param int $select
     * @desc
     */
    public function setSelect(int $select)
    {
        $this->select = $select;
    }


    /**
     * @var \Redis
     */
    private $redis;


    public function __construct(array $config)
    {
        $this->setConfig($config);
        $this->connect();
    }

    public function setConfig($config)
    {
        foreach ($config as $key => $value) {
            $function = "set" . ucfirst($key);
            if (method_exists($this, $function)) {
                $this->$function($value);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function connect()
    {
        if ($this->redis == false) {
            $this->redis = new \Redis();
            $result = $this->redis->connect($this->hostname, $this->port);
            if (!$result) {
                throw new \Exception("redis: connect to server failed");
            }
            if ($this->password and !$this->redis->auth($this->password)) {
                throw new \Exception("redis: auth failed");
            }
            $this->redis->select($this->select);
        }
    }

    public function getClient()
    {
        return $this->redis;
    }
}