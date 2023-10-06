<?php

    namespace MyApp;
    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;

    class WebSocket implements MessageComponentInterface {
        protected $clients;

        public function __construct() {
            $this->clients = new \SplObjectStorage;
        }

        public function onOpen(ConnectionInterface $conn) {
            $this->clients->attach($conn);
            echo "Cliente conectado\n";
        }

        public function onMessage(ConnectionInterface $from, $msg) {
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        }

        public function onClose(ConnectionInterface $conn) {
            $this->clients->detach($conn);
            echo "Cliente desconectado\n";
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            echo "Erro: {$e->getMessage()}\n";
            $conn->close();
        }
    }