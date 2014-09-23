## phpslow-reader
A web ui for  php slow log  analyzing.

## Installation

### download code (Git Required)
```shell
cd ${the parent path where you want to install, and the command below will make a dir named 'phpslow-reader' to this dir}
git clone https://github.com/mr5/phpslow-reader
```
For almost environment, the php-fpm progresses running with a non-administrators user(such as `www`), that means
`phpslow-reader` cannot read files that not owned by `www` user. in order to resolve it, you can run it with 
`php -S localhost:8000` command with root user and nginx proxy.

### configuration file
```shell
cp config.sample.php config.inc.php
```
Open `config.inc.php`, and offer  information of your servers, and make sure the server that  `phpslow-reader` hosted has
SSH permission to access all other servers:
```php
$config =
    array(
        'servers' => array(
            'localhost' => array(
                // In this server, 'host' field is unset, so it can be recognized as 'localhost'.
                'file' => '/var/log/php/php-slow.log'   // where your php-slow.log file saved in `localhost`
            ),
            'Server1' => array(
                'host' => '192.168.2.38',
                'file' => $_log_file    // where your php-slow.log file saved in this server
            ),
            'ServerB' => array(
                'host' => '192.168.2.39',
                'file' => $_log_file    // where your php-slow.log file saved in this server
            )
        ),
        'linesLimit' => 1000,
    );
```
