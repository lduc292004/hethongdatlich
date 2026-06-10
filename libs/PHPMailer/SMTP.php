<?php
namespace PHPMailer\PHPMailer;

class SMTP {
    const VERSION = '6.8.0';
    const LE = "\r\n";
    protected $smtp_conn;
    protected $error = ['error' => ''];

    public function connect($host, $port = null, $options = []) {
        $this->error = ['error' => ''];
        if (false === strpos($host, '://')) {
            $host = 'tcp://' . $host;
        }
        $this->smtp_conn = @stream_socket_client($host . ':' . $port, $errno, $errstr, 10);
        if (!$this->smtp_conn) {
            $this->error = ['error' => 'Khong the ket noi den server mail: ' . $errstr];
            return false;
        }
        fgets($this->smtp_conn, 512);
        return true;
    }

    public function command($command, $expect = []) {
        fputs($this->smtp_conn, $command . self::LE);
        $reply = fgets($this->smtp_conn, 512);
        return $reply;
    }

    public function getError() {
        return $this->error;
    }

    public function close() {
        if (is_resource($this->smtp_conn)) {
            fclose($this->smtp_conn);
        }
    }
}