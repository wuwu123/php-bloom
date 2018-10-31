<?php
/**
 * Created by PhpStorm.
 * User: wujie
 * Date: 2018/10/31
 * Time: 17:32
 */

namespace test;


use bloom\FilterRepeatedComments;
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{

    public function testAdd()
    {
        $config["redis"] = [
            "hostname" => "6",
            "port" => "6379",
            "password" => "6",
            "select"=>11
        ];
        $model = new FilterRepeatedComments($config);
        $value = "shhshhhhhhdidjdjjdjdd";
        $addModel = $model->add($value);
        echo "\n";
        var_dump($addModel);
        $exit = $model->exists($value);
        echo "\n";

        var_dump($exit);
        $this->assertEquals($exit ,true);
    }


    public function testDel()
    {
        $config["redis"] = [
            "hostname" => "",
            "port" => "6379",
            "password" => "0",
            "select"=>11
        ];
        $model = new FilterRepeatedComments($config);
        $value = "shhshhhhhhdidjdjjdjdd";
        $addModel = $model->del($value);
        echo "\n";
        var_dump($addModel);
        $exit = $model->exists($value);
        echo "\n";

        var_dump($exit);
        $this->assertEquals($exit ,true);
    }
}