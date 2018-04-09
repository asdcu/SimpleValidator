<?php
/**
 * Created by PhpStorm.
 * User: asd
 * Date: 2018/4/8
 * Time: 下午11:41
 */

trait Request
{
    /**
     * 支持HTTP方法
     * @var array
     */
    protected $allowMethod = ['POST', 'GET'];

    /**
     * 获取GET或POST提交过来的方法
     * @param null $key
     * @param null $default
     * @return array|null
     */
    public function rq($key = null, $default = null)
    {

        $method = $_SERVER["REQUEST_METHOD"];

        if (in_array($method, $this->allowMethod, true)) {
            //GET方法
            if ($method == 'GET') {
                return $this->get($key, $default);
            } else {
                return $this->post($key, $default);
            }
        }
        return array();
    }

    /**
     * GET方法获取参数
     * @param null $key
     * @param null $default
     * @return array|null
     */
    public function get($key = null, $default = null)
    {
        //macaw的路由方式默认未$uri&$args的所有内容，因此需要去除route中非GET过来的参数
        $request = array_filter($_GET, function ($item) {
            return $item !== "";
        });
        //key为空默认获取全部
        if (is_null($key)) {
            return $request;
        }
        if (!array_key_exists($key, $request)) {
            return $default;
        }
        return $request[$key];
    }

    /**
     * POST方法获取参数
     * @param null $key
     * @param null $default
     * @return null
     */
    public function post($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_POST;
        }
        if (!array_key_exists($key, $_POST)) {
            return $default;
        }
        return $_POST[$key];
    }
}