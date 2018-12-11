<?php

function keygen_one ( $lenght = 10) {
    
    $key = '';
    list($usec, $sec) = explode(' ', microtime());
    mt_srand((float) $sec + ((float) $usec * 100000));

    $inputs = array_merge(range('z', 'a'), range(0, 9), range('A', 'Z'));

    for($i = 0; $i < $lenght; $i++)
    {
        $key .= $inputs{mt_rand(0, 61)};
    }

    return $key;
}

?>