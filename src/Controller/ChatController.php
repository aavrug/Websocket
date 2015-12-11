<?php
namespace App\Controller;

use App\Controller\AppController;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Chat Controller
 *
 * @property \App\Model\Table\ChatTable $Chat
 */
class ChatController extends AppController implements MessageComponentInterface
{
	protected $clients;

	/**
     * __construct method
     *
     */
	public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

	/**
     * onOpen method
     *
     * @param object $conn.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
	public function onOpen(ConnectionInterface $conn) {
		// Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * onMessage method
     *
     * @param object $from, string $msg.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function onMessage(ConnectionInterface $from, $msg) {
    	$numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    /**
     * onClose method
     *
     * @param object $conn.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function onClose(ConnectionInterface $conn) {
    	// The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * onError method
     *
     * @param object $conn, object $e.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
    	echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
