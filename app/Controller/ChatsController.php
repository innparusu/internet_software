<?php
App::uses('AppController', 'Controller');

class ChatsController extends AppController {
  public $uses = array('User', 'Message');
  public $helpers = array('Js');
  public $components = array('RequestHandler','Auth', 'Cookie', 'DebugKit.Toolbar');
  public function beforefilter(){
    $this->Auth->userModel = 'User';   //認証モデル設定
    $this->Auth->allow('login','twitter','callback', 'logout');
    $this->Auth->loginRedirect = array('controller' => 'examples','action' => 'index');
    $this->Auth->logoutRedirect = array('controller' => 'examples','action' => 'logout');
    $this->Auth->loginAction = '/examples/login';

    $this->Auth->fields = array(
      'username' => 'access_token_key',
      'password' => 'access_token_secret');
    parent::beforeFilter();
  }

  public function index() {

  }

  public function send() {
    if(!$this->request->is('ajax')) { // ajaxでなければ
      $this->redirect('/');
      return;
    }
    $this->render('/Chats/chat-area', 'ajax');
  }
}
