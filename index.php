<?php

require 'super.class.php';

try {

    Super::readFile();

    Super::createTree();

    file_put_contents( 'output.json' , json_encode(Super::$arrayReturn) );

    echo '<a href="output.json" target="_blank" >открыть результат JSON</a>';

} catch (\Exception $ex) {
    echo 'Ошибка: ' . $ex->getMessage();
}
