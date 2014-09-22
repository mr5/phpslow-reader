<?
define('IN_PHP_SLOW_READER', true);
require 'config.inc.php';


function dump()
{
    echo '<pre>';
    call_user_func_array('var_dump', func_get_args());
    echo '</pre>';
}

class Console
{
    public static function log($any)
    {
        $any = json_encode($any);
        echo "<script>console.log({$any})</script>";
    }
}

function dd()
{
    call_user_func_array('dump', func_get_args());
    exit();
}

require 'PHPSlow.php';
$phpslow = new PHPSlow($config);
$tracesArr = $phpslow->getTraces($_GET['server'] ? $_GET['server'] : 'localhost');
include 'template.php';