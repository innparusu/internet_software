<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {
  public function view($id) {
    if(isset($id)){
      // $id に該当するフィールドを取得するための条件
      $conditions = array( "Post.id"  => $id);
    } else {
      $conditions = "";
    }
    // 検索結果を id 昇順で取得
    $order = array('Post.id asc');  // Post テーブル内の id が昇順となるように
    // Posts テーブルから検索する
    $post = $this->Post->find('first', array(
      'conditions' => $conditions,   // 検索条件の指定
      'order' => $order,                  // 取得順序の指定
    ));
    $this->set('post', $post);  // View(app/View/view.ctp)に $post 変数を渡す
  }

  public function add() {
    if ($this->request->isPost()) {
      if($this->Post->save($this->request->data)){
        $this->Session->setFlash('Post Saved');
      }
      $this->set('post', $this->request->data);
    }
  }
}
