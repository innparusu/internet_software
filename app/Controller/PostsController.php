<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {

  public function index() {
    // Postテーブルのすべてのデータを取り出す
    $posts = $this->Post->find('all', array(
      'order' => array('Post.id asc')
    ));
    $this->set('posts', $posts); // Viewに$posts変数を渡す
  }

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
    $this->set('post', $post);  // Viewに $post 変数を渡す
  }

  public function add() {
    // Postならばフォームからデータを取り出し,保存する
    if ($this->request->isPost()) {
      if($this->Post->save($this->request->data)){
        $this->Session->setFlash('Post Saved');
        $this->redirect('index');
      }
    }
  }

  public function edit($id) {
    // Post or Putならば引数で指定したidのデータを更新し,Viewアクションにリダイレクションする
    if ($this->request->isPost() || $this->request->isPut()) {
      $this->Post->id = $id;
      if ($this->Post->save($this->request->data)) {
        $this->Session->setFlash('Post update');
        $this->redirect(array('action' => 'view', $id));
      }
    }

    // GetならばEditViewのフォームに最初の値をセットする
    if($user = $this->Post->findById($id)) {
      $this->request->data = $user;
    } else {
      $this->Session->setFlash('No Data');
      $this->redirect('index');
    }
  }

  public function delete($id) {
    // 指定したidのデータを削除
    $this->Post->id = $id;
    if($this->Post->delete()){
      $this->Session->setFlash('Post delete');
    } else {
      $this->Session->setFlash('No Data or Delete Failed');
    }
    $this->redirect('index');
  }
}
