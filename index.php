<?php

// Bootstrap Dreamr
require_once 'core/dreamr.php';

DreamFactory::start();
$resource = DreamFactory::create_resource();
$resource->run();