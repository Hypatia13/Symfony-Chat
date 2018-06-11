<?php

namespace App\Controller;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChatController extends Controller implements MessageComponentInterface {
    protected $users;

    public function __construct() {
        $this->users = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $socket) {
        // Attach new socketection
        $this->users->attach($socket);
        echo "New user! ({$socket->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->users) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other socketection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->users as $user) {
            if ($from !== $user) {
                $user->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $socket) {
        $this->users->detach($socket);
        echo "Connection {$socket->resourceId} was terminated\n";
    }

    public function onError(ConnectionInterface $socket, \Exception $e) {
        echo "You got an error: {$e->getMessage()}\n";
        $socket->close();
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function index()
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
}


