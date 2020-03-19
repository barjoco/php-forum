#!/bin/bash

#
#   Run WebSocket PHP server, restarts when connection to client is lost
#

echo 'WebSocket server started...'
/opt/lampp/bin/php -q /opt/lampp/apps/forum/websocket.php
