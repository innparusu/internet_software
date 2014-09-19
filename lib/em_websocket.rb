require "em-websocket"

connections = []

EM::WebSocket.start(host: "192.168.33.10", port: 51234) do |ws|
  ws.onopen do
    puts "open"
    # add connections
    connections << ws
  end

  ws.onmessage do |message|
    # send_message
    connections.each{|conn| conn.send(message) }
  end

  ws.onclose do
    puts "close"
  end
end
