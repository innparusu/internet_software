require "em-websocket"
require "json"

connections = [] # 接続しているクライアントを管理する配列を定義

# ここでhostはこのスクリプトを実行しているホストのIPを指定する(ここではVagrantFileの既存のIPアドレスを指定している)
EM::WebSocket.start(host: "192.168.33.10", port: 51234) do |ws| 
  # socketが開いたらconnectionsに追加
  ws.onopen do 
    puts "open"
    connections << ws
  end

  # messageが送信されると送信されたJSONをparseし,各クライアントに送信
  ws.onmessage do |message|
    parsed_message = JSON.parse(message)
    parsed_message["body"].gsub!(/\r\n|\r|\n/, "<br />")
    connections.each{|conn| conn.send(message) }
  end

  # socketを閉じたらメッセージを表示
  ws.onclose do
    puts "close"
  end
end
