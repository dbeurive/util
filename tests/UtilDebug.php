<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
    'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use dbeurive\Util\Debug;

Debug::init();
Debug::addSection("First section");
Debug::addContent("line 1");
Debug::addContent("line 2");
Debug::addContent("line 3");
Debug::addSection("Second section");
Debug::addContent("line 1");


