<?php
// +----------------------------------------------------------------------
// | [phpslow-reader]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-9-22 15:15
// +----------------------------------------------------------------------
// + config.sample.php
// +----------------------------------------------------------------------

// This var is just for filed reusing.
$_log_file = '/tmp/php-slow.log';

$config =
    array(
        'servers' => array(
            'localhost' => array(
                // In this server, 'host' field is unset, so it can be recognized as 'localhost'.
                'file' => '/var/log/php/php-slow.log'
            ),
            'Server1' => array(
                'host' => '192.168.2.38',
                'file' => $_log_file,
                // mark code_server as 'localhost' means that direct read php codes locally .
                'code_server' => 'localhost'
            ),
            'ServerB' => array(
                'host' => '192.168.2.39',
                'file' => $_log_file
            )
        ),
        'linesLimit' => 1000,
    );