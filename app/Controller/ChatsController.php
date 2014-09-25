<?php
App::uses('AppController', 'Controller');
class ChatsController extends AppController {
  public $uses = array('User', 'Message');
  public $helpers = array('Js');
  public $components = array('RequestHandler','Auth', 'Cookie', 'DebugKit.Toolbar');

  public function beforefilter(){
    $this->Auth->userModel = 'User';   //認証モデル設定
    $this->Auth->allow('login','twitter','callback', 'logout');
    $this->Auth->loginRedirect = array('controller' => 'chats','action' => 'index');
    $this->Auth->logoutRedirect = array('controller' => 'examples','action' => 'logout');
    $this->Auth->loginAction = '/examples/login';

    $this->Auth->fields = array(
      'username' => 'access_token_key',
      'password' => 'access_token_secret');
    parent::beforeFilter();
  }

  public function index() {
    $user      = $this->Auth->user();
    $comsumer  = $this->__createComsumer();
    $this->set('user', $user);
  }

  public function send() {
    if(!$this->request->is('ajax')) { // ajaxでなければ
      $this->redirect('/');
      return;
    }
    $user      = $this->Auth->user();
    $message['body']    = $this->request->data['chat-form']['Chat-text'];
    $message['user_id'] = $user['id'];
    $this->Message->save($message);
    $this->autoRender = false;
  }

  function __createComsumer(){
    return new OAuthClient(
      getenv('TWITTER_API_KEY'),
      getenv('TWITTER_API_SECRET'));
  }
}
