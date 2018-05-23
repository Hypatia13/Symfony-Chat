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

    public function onOpen(ConnectionInterface $conn) {
        // Attach new connection
        $this->users->attach($conn); /// - 

        echo "New user! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->users) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->users as $user) {
            if ($from !== $user) {
                $user->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->users->detach($conn);

        echo "Connection {$conn->resourceId} was terminated\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "You got an error: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @Route("/", name="chat")
     */
    public function index()
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }
}


