<?php

require 'super.class.php';

try {

    Super::readFile();

    // echo '<pre>';    print_r($data, true);    echo '</pre>';
    // echo '<pre>';    print_r( Super::$arrayParser, true);    echo '</pre>';

    // echo 'arrayParser <br/>';
    // echo '<pre style="max-height: 150px; overflow: auto; border: 1px solid green;">';
    // print_r(Super::$arrayParser);
    // echo '</pre>';

    Super::createTree();

    // echo 'arrayReturn <br/>';
    // echo '<pre style="font-size:10px; max-height: 350px; overflow: auto; border: 1px solid green;">';
    // print_r(Super::$arrayReturn);
    // echo '</pre>';

    file_put_contents( 'output.json' , json_encode(Super::$arrayReturn) );

    echo '<a href="output.json" target="_blank" >открыть результат JSON</a>';

    // echo '<blockqoute style="font-size:10px">';
    // foreach (Super::$arrayReturn as $v) {
    //     // $v2 = $v['children'];
    //     // $v['children'] = [];
    //     echo '<pre>', print_r($v), '</pre>';
    // }
    // echo '</blockqoute>';


} catch (\Exception $ex) {
    echo 'Ошибка: ' . $ex->getMessage();
} catch (\Throwable $th) {
    echo 'Ошибка!: ' . $ex->getMessage();
}
