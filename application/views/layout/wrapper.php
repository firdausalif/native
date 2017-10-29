<?php
defined("BASEPATH") OR exit("no direct access allowed");

require_once('head.php');
if(isset($nav)){
    require_once($nav);
}
require_once('content.php');
require_once('footer.php');