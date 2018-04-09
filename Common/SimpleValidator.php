<?php
/**
 * Created by PhpStorm.
 * User: asd
 * Date: 2018/4/8
 * Time: 下午11:42
 */

require "Match.php";

class SimpleValidator
{
    use Match;

    protected $errors = [];

    static private $_instance; //单例模式，防止多次实例化对象

    /**
     * 禁止外部实例化对象,在实例化对象的情况下便对验证执行了操作，将错误信息记录在errors中
     * 调用验证的情况下直接返回errors即可
     * SampleValidator constructor.
     */
    private function __construct()
    {
    }

    /**
     * 禁止克隆对象
     */
    private function __clone()
    {
    }

    /**
     * 防止外部调用不存在的验证方法
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        return true;
    }

    /**
     * 单例,生成错误信息
     * @param $sources
     * @param $filter
     * @return SimpleValidator
     */
    final public static function make($sources, $filter)
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        //清空错误信息
        self::$_instance->errors = [];

        //循环调用
        foreach ($sources as $key => $value) {
            //数据源中存在而filter中不存在的内容直接跳过
            if (!array_key_exists($key, $filter))
                continue;

            //执行预操作
            self::$_instance->_prepare($key, $value, $filter[$key]);
        }

        //读取filter中存在的内容而数据源中不存在的内容
        foreach ($filter as $k => $v) {
            if (!array_key_exists($k, $sources)) {
                self::$_instance->_prepare($k, null, $v);
            }
        }

        return self::$_instance;
    }

    /**
     * 校验
     * @param $key
     * @param $source
     * @param $filter
     */
    private function _prepare($key, $source, $filter)
    {
        //循环调用,生成
        foreach ($filter as $k => $v) {
            if (is_bool($v)) {
                $bool = $this->$k($source);
            } else {
                $bool = $this->$k($source, $v);
            }
            if (!$bool) {
                $this->errors[$key][] = "$k filter invalid";
            }
        }
    }

    /**
     * 获取所有错误
     * @return array
     */
    final public function run()
    {
        return $this->errors;
    }

    /**
     * 判断是否出错
     * @return bool
     */
    final public function fails()
    {
        return empty($this->errors);
    }
}