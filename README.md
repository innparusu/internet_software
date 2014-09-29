# 使い方

## WebSocket
このChatアプリケーションではWebSocketサーバーをRubyのGemであるem-websocketを使用して実現しているため,Rubyの環境が必要となります.

### 動作環境
- Ruby 2.1.2p95
- em-websocket 0.5.1
- json 1.8.1


### gem インストール方法
```
$ gem install em-websocket
$ gem install json
```

### スクリプト実行方法
スクリプトはapp/Lib/em_websocket.rb にあります
```
$ ruby app/Lib/em_websocket.rb
```
で実行できます(誰かがsocketサーバー接続するまで何も表示されません).
#### 実行前に
実行前にスクリプトファイル内の
``` ruby
# ここでhostはこのスクリプトを実行しているホストのIPを指定する(ここではVagrantFileの既存のIPアドレスを指定している)
EM::WebSocket.start(host: "192.168.33.10", port: 51234) do |ws| 
```
とwebsocketに接続する処理が書かれているapp/webroot/js/chat.js
``` javascript
// WebSocketのインスタンスを生成(WebSocketサーバーを立ち上げているIPアドレスを指定)
ws = new WebSocket("ws://192.168.33.10:51234");
```
の部分はコメントにあるとおり,スクリプトを実行しているホストのIPアドレスを指定する必要があります。デフォルトではVagrantFileでコメント化されている"192.168.33.10"が設定されています。

## Twitter API KEY関連について
Twitter API KEY, API SECRETは(/etc/httpd/conf/httpd.conf)で環境変数を下記の名前で設定する必要があります.
- TWITTER_API_KEY 
- TWITTER_API_SECRET
