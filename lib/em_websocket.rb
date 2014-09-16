require "em-websocket"

connections = []

EM::WebSocket.start(host: "localhost", port: 51234) do |ws|
  ws.onopen do
    # add connections
    connections << ws
  end

  ws.onmessage do |message|
    # send_message
    connections.each{|conn| conn.send(message) }
  end

  ws.onclose do

  end
end
