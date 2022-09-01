<?php

class Super
{

    // массив после парсинга файла
    static $arrayParser = [];
    // массив с деревом что пишем в json
    static $arrayReturn = [];

    /**
     * читаем csv файл в массив "парсер"
     */
    static public function readFile_v1($file = 'input.csv')
    {

        $row = 1;
        if (($handle = fopen("test.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num полей в строке $row: <br /></p>\n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }

    /**
     * формируем массив дерева и пишем его в self::$arrayReturn
     */
    static public function createTree($parent = '', $type = '', $relation = '')
    {

        // echo '<Br/>';
        // echo '<Br/>';
        // echo '<Br/>1';
        // echo '<Br/>';

        self::$arrayReturn = [];

        $return = [];

        foreach (self::$arrayParser as $v) {

            if (empty($parent)) {
                if (
                    empty($v['parent']) &&
                    $v['type'] == 'Изделия и компоненты'
                ) {
                    // self::$arrayReturn[] = 1212;
                    // self::$arrayReturn[] = $v;
                    self::$arrayReturn[] = [
                        'itemName' => $v['itemName'],
                        'children' => self::createTree($v['itemName'], $v['type'])
                    ];
                }
            }

            // в parent что то указано
            else {

                $add = false;

                // if ($v['parent'] == $parent || ( !empty($relation) && $v['relation'] == $relation ) ) {
                if ($v['parent'] == $parent) {

                    if ($type == 'Изделия и компоненты' && $v['type'] == 'Изделия и компоненты') {
                        $add = true;
                    } elseif ($type == 'Изделия и компоненты' && $v['type'] == 'Варианты комплектации') {
                        $add = true;
                    }
                    //
                    elseif ($type == 'Варианты комплектации' && $v['type'] == 'Прямые компоненты') {
                        $add = true;
                    } elseif ($type == 'Прямые компоненты' && $v['type'] == 'Варианты комплектации') {
                        $add = true;
                    }

                    // echo $v['itemName'] .' / '.$v['type'].' / '. ( $add ? '+' : '-' ).'<br/>';

                // } else if ($v['itemName'] == $relation) {
                } else if ($v['parent'] == $relation) {

                    if ($type == 'Прямые компоненты' && $v['type'] == 'Варианты комплектации') {
                    $add = true;
                    }

                    // echo $v['itemName'] .' / '.$v['type'].' / '. ( $add ? '+' : '-' ).'<br/>';

                }

                // если какое либо условие прошло, то добавляем элемент в массив результата                
                if ($add === true) {
                    // $v['children'] = self::createTree($v['itemName'], $v['type'], $v['relation']);
                    // $return[] = $v;
                    $return[] = [
                        //     // self::$arrayReturn[] = $v['itemName'];
                        //     //     self::$arrayReturn[] = [
                        // 'a' => $v,
                        'itemName' => $v['itemName'],
                        // 'type' => $v['type'],
                        'parent' => $parent,
                        'children' => self::createTree($v['itemName'], $v['type'], $v['relation'])
                    ];
                }
            }
        }

        return $return;
    }


    /**
     * читаем csv файл в массив "парсер"
     */
    static public function readFile($file = 'input.csv')
    {

        self::$arrayParser = [];

        // $row = 1;
        // if ( file_exists('./data/' . $file ) ){
        //     echo __LINE__;
        // }

        // пропуск шапки данных
        $skip1 = false;
        // $skip1 = true;
        $head = [
            0 => 'itemName',
            1 => 'type',
            2 => 'parent',
            3 => 'relation'
        ];

        if (($handle = fopen('./data/' . $file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, ';')) !== FALSE) {

                if ($skip1) {

                    $da = [];
                    foreach ($head as $k => $v) {
                        $da[$v] = $data[$k];
                    }

                    self::$arrayParser[] = $da;
                } else {
                    $skip1 = true;
                    // $head = $data;
                    // foreach ($data as $k => $v) {
                    //     $head[$k] = mb_convert_case( $v, MB_CASE_FOLD_SIMPLE );
                    // }

                }
            }
            fclose($handle);
        }

        // return self::$arrayParser;

        // $loop = 0;
        // // echo '<table>';


        // // прокручиваем весь файл (так для экономии памяти)
        // foreach ($iterator as $str) {

        //     // echo $str.'<br/><br/>';
        //     self::$arrayParser[] = $str;
        //     self::$arrayParser[] = str_getcsv($str, ';');
        //     // echo  '<pre>',print_r($ar,true),'</pre><br/><br/>';
        //     // echo '<tr>'
        //     //     . '<td>' . ($ar[0] ?? '-') . '</td>'
        //     //     . '<td>' . ($ar[1] ?? '-') . '</td>'
        //     //     . '<td>' . ($ar[2] ?? '-') . '</td>'
        //     //     . '<td>' . ($ar[3] ?? '-') . '</td>'
        //     //     . '</tr>';

        //     // // прокручиваем все слова и ищем регулярками вхождения
        //     // foreach ($words as $word) {

        //     //     if (empty($word))
        //     //         continue;

        //     //     // ищем
        //     //     preg_match_all("/\b" . trim($word) . "\b/ui", $str, $matches);

        //     //     // если найдены .. то плюсуем к результату
        //     //     if (!empty($matches[0])) {
        //     //         $result[$word] += sizeof($matches[0]);
        //     //     }
        //     // }
        //     // echo ' . ';
        //     // echo ' <div>. '. strlen($str) .' +++ '. round(( $last - ( round( microtime(true) - $start, 4) ) ),4).' </div> ';
        //     // $last = round(microtime(true) - $start, 4);
        //     // flush();
        // }

        // $e = file_get_contents('./data/' . $file);
        // return $e;
    }

    static public function readTheFile($path)
    {
        // читаем по 4 строки .. получается норм по времени .. 30 сек
        $file = new SplFileObject($path);
        while (!$file->eof()) {
            $str = $file->current();
            $file->next();
            // for ($i = 0; $i < 3; $i++) {
            //     $str .= ' ' . $file->current();
            //     $file->next();
            // }
            yield $str;
        }
    }
}
