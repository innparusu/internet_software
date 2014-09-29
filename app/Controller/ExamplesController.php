<?php
class ExamplesController extends AppController {
    public $uses = array('User');
    //public $uses = array('User', 'Post');
    public $components = array('Auth', 'Cookie', 'DebugKit.Toolbar');

    public function beforefilter(){
        $this->Auth->userModel = 'User';   //認証モデル設定
        $this->Auth->allow('login', 'twitter', 'callback', 'cookieLogin', 'logout');
        $this->Auth->loginRedirect = array('controller' => 'chats','action' => 'index');
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
        } else {
          $this->Session->setFlash(__('Create Comsumer Failure'), 'default', array('class'=>'error-message'), 'auth');
        }
    }

    // 認証後、このアクションが呼ばれる
    public function callback() {
      $requestToken=$this->Session->read('twitter_request_token');
      $comsumer = $this->__createComsumer();
      $accessToken = $comsumer->getAccessToken(
        'https://api.twitter.com/oauth/access_token',
        $requestToken);

      // accessTokenからユーザー情報を取得 -> UserデータをUserテーブルに保存
      if($accessToken){
        $json=$comsumer->get(
          $accessToken->key,
          $accessToken->secret,
          'https://api.twitter.com/1.1/account/verify_credentials.json',
          array());
        $twitterData                 = json_decode($json,true);
        $user['id']                  = $twitterData['id_str'];
        $user['name']                = $twitterData['name'];
        $user['screen_name']         = $twitterData['screen_name'];
        $user['access_token_key']    = $accessToken->key;
        $user['access_token_secret'] = $accessToken->secret;
        $user['image_url']           = $twitterData['profile_image_url'];
        $this->User->save($user);

        // CookieにUserのidを追加
        $this->Cookie->write('id', $user['id']);

        // ログイン
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
      $user = $this->Auth->user();
      if(isset($user['id'])){
        return $this->redirect($this->Auth->redirect());
      }
    }

    // Cookie login
    public function cookieLogin() {
      // cookieからUserデータを取り出す
      $cookieValue = $this->Cookie->read('id');
      $user        = $this->User->read(null, $cookieValue);
      $comsumer    = $this->__createComsumer();
      
      // Userデータを更新
      $json=$comsumer->get(
        $user['User']['access_token_key'],
        $user['User']['access_token_secret'],
        'https://api.twitter.com/1.1/account/verify_credentials.json',
        array());
      $twitterData                 = json_decode($json,true);
      $user['User']['id']          = $twitterData['id_str'];
      $user['User']['name']        = $twitterData['name'];
      $user['User']['screen_name'] = $twitterData['screen_name'];
      $user['User']['image_url']   = $twitterData['profile_image_url'];
      $this->User->save($user);
      $this->Cookie->write('id', $user['User']['id']);

      // ログイン
      if ($this->Auth->login($user['User'])) {
        $this->redirect($this->Auth->redirect()/*'/examples/test'*/);
      }
      else {
        $this->redirect('index');
      }
    }

    public function logout(){
      $this->Auth->logout();
      $this->flash('再ログインはこちら',array('controller' => 'chats','action' => 'index'));
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
    }

    function __createComsumer(){
      // 環境変数からAPI_KEY, SECRETを取得(/etc/httpd/conf/httpd.confに設定)
      return new OAuthClient(
        getenv('TWITTER_API_KEY'),
        getenv('TWITTER_API_SECRET'));
    }
}
?>
