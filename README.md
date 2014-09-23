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
`php -S localhost:8000` command and nginx proxy.

### configuration file
```shell
cp config.sample.php config.inc.php
```

