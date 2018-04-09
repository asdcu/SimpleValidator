<?php
/**
 * Created by PhpStorm.
 * User: asd
 * Date: 2018/4/8
 * Time: 下午11:42
 */

trait Match {

    /**
     * 判断该字段是否为必须
     * @param $item
     * @return bool
     */
    public function required($item)
    {
        return !is_null($item);
    }

    /**
     * 判断是否为数字
     * @param $item
     * @return bool
     */
    public function numeric($item)
    {
        return is_numeric($item);
    }

    /**
     * 判断是否为数字
     * @param $item
     * @return bool
     */
    public function string($item)
    {
        return is_string($item);
    }

    /**
     * 是否为整数
     * @param $item
     * @return bool
     */
    public function integer($item)
    {
        return is_integer($item);
    }

    /**
     * 验证IP
     * @param $item
     * @return mixed
     */
    public function ip($item)
    {
        return filter_var($item, FILTER_VALIDATE_IP);
    }

    /**
     * 验证手机号
     * @param $item
     * @return false|int
     */
    public function telephone($item)
    {
        return preg_match('/^1[34578]\d{9}$/', $item);
    }

    /**
     * 判断身份证
     * @param $item
     * @return bool
     */
    public function id_card($item)
    {
        // 只能是18位
        if (strlen($item) != 18) {
            return false;
        }
        // 取出本体码
        $idCardBase = substr($item, 0, 17);
        // 取出校验码
        $verifyCode = substr($item, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verifyCodeList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += substr($idCardBase, $i, 1) * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        return $verifyCode == $verifyCodeList[$mod];
    }

    /**
     * 等于
     * @param $item
     * @param $value
     * @return bool
     */
    public function equal($item, $value)
    {
        return $item == $value;
    }

    /**
     * 不小于
     * @param $item
     * @param $min
     * @return bool
     */
    public function min($item, $min)
    {
        return !(floatval($item) < $min);
    }

    /**
     * 不大于
     * @param $item
     * @param $max
     * @return bool
     */
    public function max($item, $max)
    {
        return !(floatval($item) > $max);
    }

    /**
     * 正则接口
     * @param $item
     * @param $regex
     * @return false|int
     */
    public function regexp($item, $regex)
    {
        return preg_match($regex, $item);
    }

    /**
     * 传递闭包函数，并且该值必须作为闭包函数的仅有的一个参数，且闭包函数需要有返回值
     * 不存在返回值的情况下默认返回null,永远为false
     * @param $item
     * @param \Closure $closure
     * @return mixed
     */
    public function closure($item, \Closure $closure)
    {
        return $closure($item);
    }

    /**
     * 判断是否在数组里
     * @param $item
     * @param array $array
     * @return bool
     */
    public function in_array($item, array $array)
    {
        return in_array($item, $array, true);
    }

    /**
     * 不等于
     * @param $item
     * @param $value
     * @return bool
     */
    public function not_equal($item, $value){
        return $item != $value;
    }

}