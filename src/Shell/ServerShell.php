<?php
namespace App\Shell;

use Cake\Console\Shell;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Controller\ChatController;

class ServerShell extends Shell
{
    public function main()
    {		
	    $server = IoServer::factory(
	        new ChatController(),
	        8080
	    );

	    $server->run();
    }
}