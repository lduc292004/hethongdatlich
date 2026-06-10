<?php
namespace PHPMailer\PHPMailer;

require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';

class PHPMailer {
    public $Priority = null;
    public $CharSet = 'iso-8859-1';
    public $ContentType = 'text/plain';
    public $Encoding = '8bit';
    public $ErrorInfo = '';
    public $From = 'root@localhost';
    public $FromName = 'Root User';
    public $Sender = '';
    public $Subject = '';
    public $Body = '';
    public $Host = 'localhost';
    public $Port = 25;
    public $SMTPAuth = false;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = '';
    
    protected $MIMEHeader = '';
    protected $MIMEBody = '';
    protected $to = [];
    protected $smtp = null;

    public function isSMTP() {
        $this->smtp = new SMTP();
    }

    public function addAddress($address, $name = '') {
        $this->to[] = [$address, $name];
        return true;
    }

    public function setFrom($address, $name = '') {
        $this->From = $address;
        $this->FromName = $name;
        return true;
    }

    public function isHTML($ishtml = true) {
        if ($ishtml) {
            $this->ContentType = 'text/html';
        } else {
            $this->ContentType = 'text/plain';
        }
    }

    public function send() {
        try {
            if ($this->smtp) {
                // Thao tác bắt tay SMTP ảo để chạy thử nghiệm độc lập
                $this->smtp->connect($this->Host, $this->Port);
                $this->smtp->command("EHLO " . $this->Host);
                if ($this->SMTPAuth) {
                    $this->smtp->command("AUTH LOGIN");
                    $this->smtp->command(base64_encode($this->Username));
                    $this->smtp->command(base64_encode($this->Password));
                }
                $this->smtp->command("MAIL FROM:<" . $this->From . ">");
                foreach ($this->to as $to) {
                    $this->smtp->command("RCPT TO:<" . $to[0] . ">");
                }
                $this->smtp->command("DATA");
                
                // Gom Header và nội dung thư gửi đi
                $header = "To: " . $this->to[0][0] . "\r\n";
                $header .= "From: " . $this->FromName . " <" . $this->From . ">\r\n";
                $header .= "Subject: " . $this->Subject . "\r\n";
                $header .= "Content-Type: " . $this->ContentType . "; charset=" . $this->CharSet . "\r\n\r\n";
                
                $this->smtp->command($header . $this->Body . "\r\n.");
                $this->smtp->command("QUIT");
                $this->smtp->close();
            } else {
                // Fallback về cơ chế mail hệ thống nếu không bật SMTP
                $header = "From: " . $this->FromName . " <" . $this->From . ">\r\n";
                $header .= "Content-Type: " . $this->ContentType . "; charset=" . $this->CharSet . "\r\n";
                @mail($this->to[0][0], $this->Subject, $this->Body, $header);
            }
            return true;
        } catch (\Exception $e) {
            $this->ErrorInfo = $e->getMessage();
            return false;
        }
    }
}