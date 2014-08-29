<?php
App::import('Vendor','OAuth/OAuthClient');

class ExamplesController extends AppController {
    public $uses = array('');
    //public $uses = array('User', 'Post');
    public $components = array('Auth', 'DebugKit.Toolbar');

    public function beforefilter(){
        //$this->Auth->userModel = 'User';   //認証モデル設定
        $this->Auth->allow('login','twitter','callback' );
        $this->Auth->loginRedirect = array('controller' => 'examples','action' => 'index');
        $this->Auth->logoutRedirect = array('controller' => 'examples','action' => 'logout');
        $this->Auth->loginAction = '/examples/login';

        $this->Auth->fields = array(
            'username' => 'access_token_key',
            'password' => 'access_token_secret');
        parent::beforeFilter();
    }

    public function twitter(){
        $comsumer = $this->__createComsumer();
        $requestToken = $comsumer->getRequestToken(
            'https://api.twitter.com/oauth/request_token',
            'http://127.0.0.1:8080/examples/callback');
        print_r($requestToken);
        if ($requestToken) {
            $this->Session->write('twitter_request_token', $requestToken);
            $this->redirect('https://api.twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        }
    }

    // 認証後、このアクションが呼ばれる
    public function callback() {
        $requestToken=$this->Session->read('twitter_request_token');
        $comsumer = $this->__createComsumer();
        $accessToken = $comsumer->getAccessToken(
            'https://api.twitter.com/oauth/access_token',
            $requestToken);

        if($accessToken){
            $json=$comsumer->get(
                $accessToken->key,
                $accessToken->secret,
                'https://api.twitter.com/1.1/account/verify_credentials.json',
                array());
            $twitterData = json_decode($json,true);
            /*
            $this->User->update(
                            Array(
                                "id" => $twitterData['id_str'],
                                "name" => $twitterData['screen_name'],
                                "access_token_key" => $accessToken->key,
                                "access_token_secret" => $accessToken->secret,
                            ));
             */
            $user['access_token_key'] = $accessToken->key;
            $user['access_token_secret'] = $accessToken->secret;
            $user['twitter_id'] = $twitterData['id_str'];
            $user['screen_name'] = $twitterData['screen_name'];

            if ($this->Auth->login($user)) {
                $this->redirect($this->Auth->redirect()/*'/examples/test'*/);
            }
            else {
                $this->redirect('index');
            }
        } else {
            $this->redirect('index');
        }

    }
    public function login(){
        //echo 'called from:'.$this->Auth->loginRedirect;
        $user = $this->Auth->user();

        if(isset($user['example_name'])){
            $this->redirect($this->Auth->loginRedirect);
        } else if($this->request->is('post')){
            if($this->Auth->login()){
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Authentication Failure'), 'default', array('class'=>'error-message'), 'auth');
            }
        }

    }

    public function logout(){
        $this->Auth->logout();
        $this->flash('トップページにとぶ','index');
    }

    public function index() {
        $users =$this->Auth->user();
        $comsumer = $this->__createComsumer();

        $twitterData="";
        $json=$comsumer->get(
            $users['access_token_key'],
            $users['access_token_secret'],
            'https://api.twitter.com/1.1/statuses/home_timeline.json',
            array('count' => '30')
        );
        $twitterData = json_decode($json,true);

        $this->set(compact(
            'users',
            'twitterData'
        ));
        //print_r($data);
    }

    function __createComsumer(){
        return new OAuthClient(
            getenv('TWITTER_API_KEY'),
            getenv('TWITTER_API_SECRET'));
    }
}
?>
