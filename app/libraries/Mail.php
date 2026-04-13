<?php
/**
 * Simple SMTP mail sender - pure PHP, no external libraries.
 * Used for forgot-password and other transactional emails when SMTP is configured.
 */
class Mail {
    private $host;
    private $port;
    private $secure;
    private $username;
    private $password;
    private $socket;
    private $lastError = '';

    public function __construct() {
        $this->host    = defined('SMTP_HOST') ? SMTP_HOST : '';
        $this->port    = defined('SMTP_PORT') ? (int) SMTP_PORT : 587;
        $this->secure  = defined('SMTP_SECURE') ? strtolower(SMTP_SECURE) : 'tls';
        $this->username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
        $this->password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
    }

    /**
     * Check if SMTP is configured (host set).
     */
    public static function isConfigured() {
        return defined('SMTP_HOST') && trim(SMTP_HOST) !== '';
    }

    /**
     * Send an email via SMTP.
     * @param string $to Recipient email
     * @param string $subject Subject line
     * @param string $body Plain text body
     * @param string $fromEmail From address
     * @param string $fromName From display name (optional)
     * @return bool True on success
     */
    public function send($to, $subject, $body, $fromEmail, $fromName = '') {
        $this->lastError = '';
        $from = $fromName ? $fromName . ' <' . $fromEmail . '>' : $fromEmail;

        $useSSL = ($this->secure === 'ssl');
        $scheme = $useSSL ? 'ssl://' : 'tcp://';
        $target = $scheme . $this->host . ':' . $this->port;

        $errno = 0;
        $errstr = '';
        $ctx = stream_context_create();
        $this->socket = @stream_socket_client(
            $target,
            $errno,
            $errstr,
            15,
            STREAM_CLIENT_CONNECT,
            $ctx
        );

        if (!$this->socket) {
            $this->lastError = "Connection failed: $errstr ($errno)";
            return false;
        }

        stream_set_timeout($this->socket, 15);

        if (!$this->readResponse(220)) {
            $this->close();
            return false;
        }

        $this->sendLine('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        if (!$this->readResponse(250)) {
            $this->close();
            return false;
        }

        if (!$useSSL && $this->secure === 'tls') {
            $this->sendLine('STARTTLS');
            if (!$this->readResponse(220)) {
                $this->close();
                return false;
            }
            if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->lastError = 'STARTTLS failed';
                $this->close();
                return false;
            }
            $this->sendLine('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
            if (!$this->readResponse(250)) {
                $this->close();
                return false;
            }
        }

        if ($this->username !== '' && $this->password !== '') {
            $this->sendLine('AUTH LOGIN');
            if (!$this->readResponse(334)) {
                $this->close();
                return false;
            }
            $this->sendLine(base64_encode($this->username));
            if (!$this->readResponse(334)) {
                $this->close();
                return false;
            }
            $this->sendLine(base64_encode($this->password));
            if (!$this->readResponse(235)) {
                $this->lastError = 'SMTP authentication failed';
                $this->close();
                return false;
            }
        }

        $this->sendLine('MAIL FROM:<' . $this->dotStrip($fromEmail) . '>');
        if (!$this->readResponse(250)) {
            $this->close();
            return false;
        }
        $this->sendLine('RCPT TO:<' . $this->dotStrip($to) . '>');
        if (!$this->readResponse(250)) {
            $this->close();
            return false;
        }
        $this->sendLine('DATA');
        if (!$this->readResponse(354)) {
            $this->close();
            return false;
        }

        $headers = "From: $from\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "\r\n";
        $data = $headers . str_replace("\n.", "\n..", $body) . "\r\n.";
        $this->sendLine($data);

        if (!$this->readResponse(250)) {
            $this->close();
            return false;
        }
        $this->sendLine('QUIT');
        $this->readResponse(221);
        $this->close();
        return true;
    }

    public function getLastError() {
        return $this->lastError;
    }

    private function sendLine($line) {
        fwrite($this->socket, $line . "\r\n");
    }

    private function readResponse($expectCode) {
        $code = (int) $expectCode;
        while ($line = fgets($this->socket, 512)) {
            $got = (int) substr($line, 0, 3);
            if ($got !== $code && ($got < 200 || $got > 399)) {
                $this->lastError = trim($line);
                return false;
            }
            if (isset($line[3]) && $line[3] !== ' ') {
                continue;
            }
            return true;
        }
        $this->lastError = 'No response from server';
        return false;
    }

    private function dotStrip($email) {
        return trim(preg_replace('/\s+/', '', $email));
    }

    private function close() {
        if (is_resource($this->socket)) {
            fclose($this->socket);
            $this->socket = null;
        }
    }
}
