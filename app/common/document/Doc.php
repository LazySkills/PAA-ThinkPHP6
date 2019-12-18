<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-02  */

namespace app\common\document;


use Doctrine\Common\Annotations\Annotation;
use \app\common\authorize\Jwt;
use think\facade\Session;
use think\facade\View;

final class Doc
{
    /**
     * @var \think\route\RuleItem $rule
     */
    protected $rule;

    /**
     * @var \think\Route $router
     */
    protected $router;

    /**
     * @var \app\common\authorize\Jwt
     */
    protected $jwt;

    /** @var array */
    public $annotation = [];

    protected $path = '/public/annotation.json';

    public function __construct()
    {
        $this->jwt = new Jwt();
        $this->setAnnotationDoc();
    }

    public function setAnnotationDoc()
    {
        $this->annotation = is_file(root_path() . $this->path) ?
            json_decode(file_get_contents(root_path() . $this->path), true) :
            [];
    }

    public function setPaaRoute(\think\route\RuleItem &$rule)
    {
        $this->rule = $rule;
        if (config('annotation.management') === true) {
            $router = function () {
                return $this->router;
            };
            $this->router = $router->call($rule);
            $this->router->group('', function () {
                $this->setPaaLoginRoute();
                $this->setPaaLoginInRoute();
                $this->setPaaIndexRoute();
                $this->setPaaWelcomeRoute();
                $this->setPaaInfoRoute();
                $this->setPaaEditRoute();
                $this->setPaaEditSaveRoute();
                $this->setPaaLoginOutRoute();
                $this->setPaaRefreshRoute();
            })->middleware([\think\middleware\SessionInit::class]);
        }else{
            throw new \Exception("注解配置文件中'annotation.management'应该为true");
        }
    }

    /** 登陆操作 */
    private function setPaaLoginInRoute(): void
    {
        if (!empty($this->router->getRule('paa/login/in'))) return;
        $this->router->post('paa/login/in', function () {
            if (request()->isPost()) {
                $name = input('username');
                $password = input('password');
                if ($name == 'admin' and $password == 'supper') {
                    $jwt = $this->jwt->encode($name, '1');
                    Session::set('apiAuthorize', json_encode(input()));
                    Session::set('isEdit', true);
                    return json([
                        'msg' => '登录成功',
                        'code' => 200,
                        'data' => [
                            'url' => '/paa/index?token=' . $jwt['refresh_token']
                        ]
                    ], 200);
                }
                if ($name == 'web' and $password == '123456') {
                    $jwt = $this->jwt->encode($name, '0');
                    return json([
                        'msg' => '登录成功',
                        'code' => 200,
                        'data' => [
                            'url' => '/paa/index?token=' . $jwt['refresh_token']
                        ]
                    ], 200);
                }
            }
            throw new \Exception('登录失败');
        });
    }

    /** 登陆页面 */
    private function setPaaLoginRoute(): void
    {
        if (!empty($this->router->getRule('paa/login'))) return;
        $this->router->get('paa/login', function () {
            return View::display(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'annotation.doc.login.stub'));
        });
    }

    /** 欢迎页面👏 */
    private function setPaaWelcomeRoute(): void
    {
        if (!empty($this->router->getRule('paa/welcome'))) return;
        $this->router->get('paa/welcome', function () {
            return View::display(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'annotation.doc.welcome.stub'));
        });
    }

    /** 设置接口管理平台首页 */
    private function setPaaIndexRoute(): void
    {
        if (!empty($this->router->getRule('paa/index'))) return;
        $this->router->get('paa/index', function () {
            $jwt = $this->checkUserLogin();
            View::assign('isEdit', $jwt['signature']);
            $annotations = $this->toArray();
            View::assign('menus', $annotations);
            View::assign('token', input('token'));
            return View::display(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'annotation.doc.index.stub'));
        });
    }

    /** 设置接口管理平台接口详情🔎 */
    private function setPaaInfoRoute(): void
    {
        if (!empty($this->router->getRule('paa/info'))) return;
        $this->router->get('paa/info', function () {
            $params = request()->get();
            $apiInfo = $this->getUserAnnotationJson($params['rule']);
            $apiInfo['validate'] = $apiInfo['validate'][0];
            foreach ($apiInfo['validate'] as $key => $item) {
                $validateName = explode('|', $key);
                $apiInfo['validate'][$key] = [
                    'name' => $validateName[0],
                    'doc' => $validateName[1] ?? '',
                    'rule' => $item
                ];
            }
            $apiInfo['success'] = json_encode($apiInfo['success']);
            $apiInfo['error'] = json_encode($apiInfo['error']);
            return View::display(
                file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'annotation.doc.info.stub'),
                ['info' => $apiInfo, 'title' => 'API接口详情', 'isEdit' => Session::get('isEdit')]
            );
        });
    }

    /** 编辑接口文档 */
    private function setPaaEditRoute(): void
    {
        if (!empty($this->router->getRule('paa/edit'))) return;
        $this->router->get('paa/edit', function () {
            $jwt = $this->checkUserLogin();
            if ($jwt['signature'] != 1) {
                throw new \Exception('你没有编辑权限');
            }
            $params = request()->get();
            $apiInfo = $this->getUserAnnotationJson($params['rule']);
            $apiInfo['validate'] = $apiInfo['validate'][0];
            foreach ($apiInfo['validate'] as $key => $item) {
                $validateName = explode('|', $key);
                $apiInfo['validate'][$key] = [
                    'name' => $validateName[0],
                    'doc' => $validateName[1] ?? '',
                    'rule' => $item
                ];
            }
            $token = $params['token'];
            $apiInfo['success'] = json_encode($apiInfo['success']);
            $apiInfo['error'] = json_encode($apiInfo['error']);
            return View::display(
                file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'annotation.doc.edit.stub'),
                ['info' => $apiInfo,'token'=>$token, 'title' => '编辑API接口', 'isEdit' => Session::get('isEdit')]
            );
        });
    }

    /** 保存接口文档 */
    private function setPaaEditSaveRoute(): void
    {
        if (!empty($this->router->getRule('paa/edit/save'))) return;
        $this->router->post('paa/edit/save', function () {
            if (request()->isPost()) {
                $params = input();
                $jwt = $this->checkUserLogin();
                $return_params = [];
                if (isset($params['return_params']['name']) and !empty($params['return_params']['name'])) {
                    foreach ($params['return_params']['name'] as $key => $value) {
                        $return_params[$value] = $params['return_params']['value'][$key];
                    }
                }
                $success = json_decode($params['success'], true);
                $error = json_decode($params['error'], true);
                if (is_null($error) or is_null($success)) {
                    throw new \Exception('返回值格式为：Json');
                }
                $apiInfo = $this->getUserAnnotationJson($params['rule']);
                $apiInfo['success'] = $success;
                $apiInfo['error'] = $error;
                $apiInfo['return_params'] = $return_params;
                $this->setUserAnnotationJson($params['rule'],$apiInfo);
                return json([
                    'msg' => '操作成功',
                    'code' => 200,
                    'data' => []
                ], 200);
            }
            throw new \Exception('保存失败，返回值格式为：Json');
        });
    }

    /** 设置接口管理平台退出接口 */
    private function setPaaLoginOutRoute(): void
    {
        if (!empty($this->router->getRule('paa/login/out'))) return;
        $this->router->get('paa/login/out', function () {
            redirect('/paa/login')->send();
            exit;
        });
    }

    // 刷新注解文档
    private function setPaaRefreshRoute(): void
    {
        if (!empty($this->router->getRule('paa/refresh'))) return;
        $this->router->get('paa/refresh', function () {
            foreach ($this->annotation as $key => $item) {
                if (empty($this->router->getRule($key))) {
                    unset($this->annotation[$key]);
                }
            }
            $this->setApiAnnotationJson($this->annotation);
            redirect('/paa/index?token=' . input('token'))->send();
            exit;
        });
    }

    /** 检查用户登陆 */
    public function checkUserLogin()
    {
        try {
            $token = input('token');
            if (empty($token)) {
                redirect('/paa/login')->send();
                exit;
            }
            $jwt = $this->jwt->decode($token);
            if (!isset($jwt['uniqueId'])) {
                redirect('/paa/login')->send();
                exit;
            }
            return $jwt;
        } catch (\Exception $exception) {
            redirect('/paa/login')->send();
            exit;
        }
    }

    /**
     * 设置用户注释json文件
     */
    public function setUserAnnotationJson(string $rule, array $docs = [])
    {
        $this->annotation[$rule] = $docs;
        $res = file_put_contents(
            root_path() . $this->path,
            json_encode($this->annotation, JSON_UNESCAPED_UNICODE),
            FILE_USE_INCLUDE_PATH
        );
        return $res;
    }

    /** 获取用户注解json文件 */
    public function getUserAnnotationJson(string $rule)
    {
        return $this->annotation[$rule] ?? [];
    }

    /** 获取注解json文件 */
    public function getApiAnnotationJson()
    {
        return $this->annotation ?? [];
    }

    /** 设置注释json文件 */
    public function setApiAnnotationJson(array $apis)
    {
        $this->annotation = $apis;
        $res = file_put_contents(
            root_path() . $this->path,
            json_encode($this->annotation, JSON_UNESCAPED_UNICODE),
            FILE_USE_INCLUDE_PATH
        );
        return $res;
    }

    /** 初始化注解Json数据 */
    public function initializeAnnotationJson(Annotation $annotation): void
    {
        $data = $this->getApiAnnotationJson();
        $ruleData = $this->getRuleData(
            $data[$this->rule->getRule()] ?? [],
            $this->rule
        );
        if (empty($annotation->value)) {
            return;
        }
        $ruleData['doc'] = empty($annotation->group) ? $annotation->value: $annotation->group . '.' . $annotation->value;
        $ruleData['hide'] = $annotation->hide == 'false' ? false : true;
        $data[$this->rule->getRule()] = $ruleData;
        $this->setApiAnnotationJson($data);
    }

    /** 获取注解路由数据 */
    private function getRuleData(array $api, \think\route\RuleItem $rule)
    {
        return [
            'rule' => $rule->getRule(),
            'route' => $rule->getRoute(),
            'method' => $rule->getMethod(),
            'validate' => $rule->getOption('validate'),
            'success' => $api['success'] ?? [],
            'error' => $api['error'] ?? [],
            'return_params' => $api['return_params'] ?? []
        ];
    }

    // 获取注解数据
    public function toArray()
    {
        $annotations = [];
        foreach ($this->annotation as $key => $item) {
            $annotations = $this->getRuleItem($item, $annotations);
        }
        return $annotations;
    }

    public function getRuleItem( array $item = [], array $annotations = [])
    {
        $ruleArr = explode('.', trim($item['doc'], '.'));
        switch (count($ruleArr)) {
            case 3:
                $annotations[$ruleArr[0]][$ruleArr[1]][$ruleArr[2]] = $item;
                return $annotations;
            case 2:
                $annotations[$ruleArr[0]][$ruleArr[1]] = $item;
                return $annotations;
            case 1:
                $annotations[$ruleArr[0]] = $item;
                return $annotations;
        }
    }

}
