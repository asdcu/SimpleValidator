<?php
/**
 * Created by PhpStorm.
 * User: asd
 * Date: 2018/4/8
 * Time: 下午11:32
 */

require "Common/Request.php";
require "Common/SimpleValidator.php";

class Application {

    use Request;

    public function __call($name, $arguments)
    {
        echo "no method match!";
    }

    public function run() {
        $uri = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
        $method = ltrim($uri, '/');
        $this->$method();
    }

    public function register() {
        //验证接收的内容
        $validator = SimpleValidator::make($this->get(), [
            'name' => [
                'required' => true,
                'string' => true,
                'closure' => function ($item) {
                    return mb_strlen($item) >= 2 && mb_strlen($item) <= 5;
                },
                'in_array' => ['asd', 'bcd', 'dd']
            ],
            'age' => [
                'required' => true,
                'integer' => true,
                'min' => 10,
                'max' => 55
            ],
            'tel' => [
                'required' => true,
                'telephone' => true
            ],
            'email' => [
                'required' => true,
                'email' => true
            ],
            'password' => [
                'required' => true,
                'regexp' => '/[a-zA-Z0-9]{6,}/'
            ]
        ]);

        if ($validator->fails() === false) {
            echo '<pre>';
            var_dump($validator->run());exit();
        }

        echo "验证通过";
    }

}

$app = new Application();
$app->run();