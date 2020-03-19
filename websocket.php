<?

// Addr and ip
$address = '0.0.0.0';
$port = 12347;

// Create WebSocket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

// Bind the socket to an address/port
socket_bind($socket, $address, $port) or die('Could not bind to address');

// Start listening for connections
socket_listen($socket);

// Non blocking socket type
socket_set_nonblock($socket);

// Clients array
$clients = [];

// Send a message to socket
function send_msg($string, ...$sockets)
{
    foreach ($sockets as $socket) {
        $response = chr(129) . chr(strlen($string)) . $string;
        if (!@socket_write($socket, $response) && count($sockets) === 1) {
            return false;
        }
    }
    return true;
}

while (true) {
    // Accept new connections
    if ($client = socket_accept($socket)) {
        // Send WebSocket handshake headers.
        $request = socket_read($client, 5000);
        preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
        $key = base64_encode(pack('H*', sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $headers = "HTTP/1.1 101 Switching Protocols\r\n";
        $headers .= "Upgrade: websocket\r\n";
        $headers .= "Connection: Upgrade\r\n";
        $headers .= "Sec-WebSocket-Version: 13\r\n";
        $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
        socket_write($client, $headers, strlen($headers));

        if (is_resource($client)) {
            socket_set_nonblock($client);
            // Acknowledge client
            send_msg('Hello from server', $client);
            // Add client to clients array
            $clients[] = $client;
            // Report added client on the server
            $cid = count($clients) - 1;
            echo "Client $cid connected\n";
        }
    }

    // Check clients for any messages that have been sent
    foreach ($clients as $i => $client) {
        // Remove any clients that have left
        if (!send_msg('ping', $client)) {
            @socket_close($client);
            array_splice($clients, $i, 1);
            echo "Client $i disconnected\n";
        } else {
            // Check if a client has sent a message
            $message = socket_read($client, 1024);
            if ($message) echo "Msg received from client $i: $message\n";
        }
    }

    sleep(1);
}

// Close the master socket
socket_close($socket);
