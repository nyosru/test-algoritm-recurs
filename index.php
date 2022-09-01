<?php

require 'GenTree.class.php';

try {


    $memory = memory_get_usage();
    $start = microtime(true);

    GenTree::readFile();

    GenTree::createTree();

    file_put_contents( 'output.json' , json_encode(GenTree::$arrayReturn , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

    echo '<a href="output.json" target="_blank" >открыть результат JSON</a>';

    echo '<Br/>';
    echo 'Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
    echo '<br/>';
    echo 'Использовано памяти: ' . round((memory_get_usage() - $memory) / 1024, 2) . ' Kбайт';
    

} catch (\Exception $ex) {
    echo 'Ошибка: ' . $ex->getMessage();
}
