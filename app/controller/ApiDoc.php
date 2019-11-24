<?php


namespace app\controller;


use app\BaseController;
use think\response\Html;

class ApiDoc extends BaseController
{
    protected $path = '/public/annotation.json';

    public function index(){
        if (!session('?apiAuthorize') and request()->action() != 'login'){
            return redirect('/apidoc/login');
        }
        return display('');
    }

    public function main(){
        return display('');
    }

    public function login(){
        return view();
    }

    public function list(){
        return view('',['edit'=>session('isEdit')]);
    }

    public function getList(){
        $apiList = $this->getApiDocPaginate();
        return json([
            'msg'=>'获取成功',
            'code' => 200,
            'count'=>$apiList['count'],
            'data'=>$apiList['data']
        ],200);
    }

    public function info(){
        $params = request()->get();
        $apiList = json_decode(file_get_contents(root_path().$this->path),true);
        $apiInfo = $apiList[$params['group']][$params['action']];
        $apiInfo['validate'] = $apiInfo['validate'][0];
        foreach ($apiInfo['validate'] as $key => $item){
            $validateName = explode('|',$key);
            $apiInfo['validate'][$key] = [
                'name' => $validateName[0],
                'doc' => $validateName[1] ?? '',
                'rule' => $item
            ];
        }
        $apiInfo['action'] = $params['action'];
        $apiInfo['group'] = $params['group'];
        $apiInfo['success'] = json_encode($apiInfo['success']);
        $apiInfo['error'] = json_encode($apiInfo['error']);
        return view('',['info'=>$apiInfo,'title'=>'API接口详情','isEdit'=>session('isEdit')]);
    }

    public function edit(){
        if (session('isEdit') !== true){
            throw new \Exception('你没有编辑权限');
        }
        $params = request()->get();
        $apiList = json_decode(file_get_contents(root_path().$this->path),true);
        $apiInfo = $apiList[$params['group']][$params['action']];
        $apiInfo['validate'] = $apiInfo['validate'][0];
        foreach ($apiInfo['validate'] as $key => $item){
            $validateName = explode('|',$key);
            $apiInfo['validate'][$key] = [
                'name' => $validateName[0],
                'doc' => $validateName[1] ?? '',
                'rule' => $item
            ];
        }
        $apiInfo['action'] = $params['action'];
        $apiInfo['group'] = $params['group'];
        $apiInfo['success'] = json_encode($apiInfo['success']);
        $apiInfo['error'] = json_encode($apiInfo['error']);
        return view('',['info'=>$apiInfo,'title'=>'编辑API接口','isEdit'=>session('isEdit')]);
    }

    public function save(){
        if (request()->isPost()){
            $params = input();
            $return_params = [];
            if (isset($params['return_params']['name']) and !empty($params['return_params']['name'])){
                foreach ($params['return_params']['name'] as $key => $value){
                    $return_params[$value] = $params['return_params']['value'][$key];
                }
            }
            $success = json_decode($params['success'],true);
            $error = json_decode($params['error'],true);
            if (is_null($error) or is_null($success)){
                throw new \Exception('返回值格式为：Json');
            }
            $apiList = json_decode(file_get_contents(root_path().$this->path),true);
            $apiList[$params['group']][$params['action']]['success'] = $success;
            $apiList[$params['group']][$params['action']]['error'] = $error;
            $apiList[$params['group']][$params['action']]['return_params'] = $return_params;
            file_put_contents(root_path().$this->path,
                json_encode($apiList),
                FILE_USE_INCLUDE_PATH
            );
            return json([
                'msg'=>'操作成功',
                'code'=>200,
                'data'=>[]
            ],200);
        }
        throw new \Exception('保存失败，返回值格式为：Json');
    }

    public function loginIn(){
        if (request()->isPost()){
            if (input('username') == 'admin' and input('password') == 'supper'){
                session('apiAuthorize',json_encode(input()));
                session('isEdit',true);
                return json([
                    'msg'=>'登录成功',
                    'data'=>[
                        'url' => '/apidoc/index'
                    ]
                ],200);
            }
            if (input('username') == 'web' and input('password') == '123456'){
                session('apiAuthorize',json_encode(input()));
                session('isEdit',false);
                return json([
                    'msg'=>'登录成功',
                    'data'=>[
                        'url' => '/apidoc/index'
                    ]
                ],200);
            }
        }
        throw new \Exception('登录失败');
    }

    public function outLogin(){
        session('apiAuthorize',null);
        return redirect('/apidoc/login');
    }

    protected function getApiDocPaginate(){
        $apiList = json_decode(file_get_contents(root_path().$this->path),true);
        $apiDocs = [];
        $keyword = input('keyword');
        foreach($apiList as $key => $list){
            foreach($list as $doc => $route){
                if (session('isEdit') != true && $route['hide'] == true) continue;
                if (empty($keyword) or stristr($key,$keyword) or stristr($doc,$keyword)){
                    $route['action'] = $doc;
                    $route['group'] = $key;
                    unset($route['validate']);
                    array_push($apiDocs,$route);
                }
            }
        }
        return [
            'data' => $this->paginate($apiDocs),
            'count' => count($apiDocs)
        ];
    }

    protected function paginate(array $data){
        $page = input('page') ?? 1;
        $limit = input('limit') ?? 10;
        return array_chunk($data,$limit,true)[$page -1];
    }
}