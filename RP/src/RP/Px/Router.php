<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-3-11
 * Time: 下午10:55
 */

namespace RP\Px;


class Router
{
    protected $baseUrl = '';
    protected $parent = null;
    protected $urlMappings = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
    );
    protected $subRouters = array();

    public function __construct()
    {
        $this->routes();
    }

    public function includeActions($controllers)
    {
        if (!is_array($controllers)) {
            $controllers = array($controllers);
        }
        foreach ($controllers as $controller) {
            $this->subRouters[] = $controller;
        }
    }

    protected function get($urlPattern, $actionName)
    {
        $this->addRoute('GET', $urlPattern, $actionName);
    }

    public function addRoute($method, $urlPattern, $actionName)
    {
        if (is_array($urlPattern)) {
            foreach ($urlPattern as $item) {
                $this->addRoute($method, $item, $actionName);
            }
        } else {
            $pattern = new UrlPattern($urlPattern);
            $this->urlMappings[$method][] = array(
                'pattern' => $pattern,
                'action' => $actionName,
            );
        }
    }

    protected function post($urlPattern, $actionName)
    {
        $this->addRoute('POST', $urlPattern, $actionName);
    }

    protected function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 在请求的action之前调用，如果返回false，就表示停止执行action，直接进入_afterAction
     * $args是路径匹配的参数
     */
    protected function _beforeAction($args)
    {
        return true;
    }

    /**
     * 在请求的action之后调用，这个方法的结果作为最终的结果输出
     * $response 是之前action的输出结果
     * $args是路径匹配的参数
     */
    protected function _afterAction($response, $args)
    {
        return $response;
    }

    protected function getMethodByName($name)
    {
        $reflectionClass = new \ReflectionClass(get_called_class());
        if (!$reflectionClass->hasMethod($name)) {
            return false;
        }
        $method = $reflectionClass->getMethod($name);
        return $method;
    }

    /**
     * 开始调度请求
     */
    public function dispatch($url)
    {
        $fullBaseUrl = $this->getFullBaseUrl();
        $method = $this->getRequestMethod();
        // 先匹配urlMappings
        foreach ($this->urlMappings[$method] as $urlMapping) {
            $pattern = $urlMapping['pattern'];
            $action = $urlMapping['action'];
            $params = $pattern->match($fullBaseUrl, $url);
            // TODO: 增加一个属性，当这个属性为真时表示自动给这个类的所有以action开头的函数都加上一个默认的url pattern(baseUrl/$MethodName)
            if ($params !== false) {
                // match the url, and to call the action with the params
//                var_dump($params);
                // TODO: check if $action is a method in $this
                $res = $this->_beforeAction($params);
                if (!$res) {
                    return ''; // 匹配了，但是结果为空（因为前置处理失败，如果要输出，在_beforeAction中处理）
                }
                $actionMethod = $this->getMethodByName($action);
                if (!$actionMethod) {
                    throw new \RuntimeException("Can't find action $action in " . get_called_class());
                }
                // TODO: 判断参数数量，提供默认值
                $res = $actionMethod->invokeArgs($this, $params);
                $res = $this->_afterAction($res, $params);
                return $res;
            }
        }
        // 然后匹配subRouters
        foreach ($this->subRouters as $routerClass) {
            $router = new $routerClass();
            $router->parent = $this;
            $fullBaseUrl = $router->getFullBaseUrl();
            if ($fullBaseUrl !== '' && strpos($url, $fullBaseUrl) !== 0) {
                continue;
            }
            $router->routes();
            $res = $router->dispatch($url);
            if ($res !== false) {
                return $res;
            }
        }
        return false;
    }

    /**
     * 子类可以覆盖这个方法，这个方法会在本对象创建时执行，从而加载路由表
     */
    protected function routes()
    {

    }

    /**
     * 根据从当前routable开始一层层父级，获取将$baseUrl合并起来的结果
     */
    public function getFullBaseUrl()
    {
        $path = '';
        $cur = $this;
        do {
            $path = $cur->baseUrl . $path;
            $cur = $cur->parent;
        } while ($cur != null);
        return $path;
    }

    public function urlFor($params = array())
    {
        // TODO
    }

} 