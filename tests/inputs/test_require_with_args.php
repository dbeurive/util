<?php

return call_user_func(function($inVar) {

    echo "Got $inVar\n";
    return [2*$inVar];

}, $parameter);
