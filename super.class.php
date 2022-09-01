<?php

class Super
{

    // массив после парсинга файла
    static $arrayParser = [];
    // массив с деревом что пишем в json
    static $arrayReturn = [];

    /**
     * формируем массив дерева и пишем его в self::$arrayReturn
     */
    static public function createTree($parent = '', $type = '', $relation = '')
    {
        self::$arrayReturn = [];
        $return = [];

        foreach (self::$arrayParser as $v) {

            if (empty($parent)) {
                if (
                    empty($v['parent']) &&
                    $v['type'] == 'Изделия и компоненты'
                ) {
                    self::$arrayReturn[] = [
                        'itemName' => $v['itemName'],
                        'children' => self::createTree($v['itemName'], $v['type'])
                    ];
                }
            }

            // в parent что то указано
            else {

                $add = false;

                if ($v['parent'] == $parent) {

                    if ($type == 'Изделия и компоненты' && $v['type'] == 'Изделия и компоненты') {
                        $add = true;
                    } elseif ($type == 'Изделия и компоненты' && $v['type'] == 'Варианты комплектации') {
                        $add = true;
                    } elseif ($type == 'Варианты комплектации' && $v['type'] == 'Прямые компоненты') {
                        $add = true;
                    } elseif ($type == 'Прямые компоненты' && $v['type'] == 'Варианты комплектации') {
                        $add = true;
                    }
                } else if ($v['parent'] == $relation) {
                    if ($type == 'Прямые компоненты' && $v['type'] == 'Варианты комплектации') {
                        $add = true;
                    }
                }

                // если какое либо условие прошло, то добавляем элемент в массив результата                
                if ($add === true) {
                    $return[] = [
                        'itemName' => $v['itemName'],
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

        // пропуск шапки данных
        $skip1 = false;
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
                }
            }
            fclose($handle);
        }
    }
}
