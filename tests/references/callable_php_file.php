
<?php
return call_user_func(function() {
    $data = <<<EOS
a:3:{i:0;s:1:"A";i:1;s:1:"B";i:2;s:1:"C";}
EOS;
    return unserialize($data);
});