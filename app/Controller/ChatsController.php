<?php
App::uses('AppController', 'Controller');
/**
 * Chats Controller
 *
 */
class ChatsController extends AppController {
  public $helpers = array('Js');
  public $components = array('RequestHandler');
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
