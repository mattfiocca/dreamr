<?php

require_once 'dreamfactory.php';
require_once 'dreamresource.php';

DreamFactory::fireup();

$resource = DreamFactory::create_resource();
$resource->route();
$resource->output();