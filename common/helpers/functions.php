<?php


/**
 * Dump And DIE!!!
 *
 * @param $var
 */
function dd($var)
{
    print_r($var);
    exit;
}

/**
 * Dump
 *
 * @param $var
 */
function dump($var)
{
    print_r($var);
}

/**
 *  fix for php version <7.3
 */
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        $arr = array_keys($arr);
        $key = array_shift($arr);
        return $key;
    }
}

if (!function_exists('array_only')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     */
    function array_only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        return getenv($key) ?: $default;
    }
}

if (!function_exists('array_pluck')) {
    /**
     * Pluck an array of values from an array.
     *
     * @param array  $array
     * @param string $value
     * @param string $key
     *
     * @return array
     */
    function array_pluck($array, $value, $key = null)
    {
        $results = array();

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }
}

if (!function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed  $target
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($target)) {
                if (!array_key_exists($segment, $target)) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif ($target instanceof ArrayAccess) {
                if (!isset($target[$segment])) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif (is_object($target)) {
                if (!isset($target->{$segment})) {
                    return value($default);
                }

                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}

if (!function_exists('batch_insert_array')) {
    /**
     * Массовая вствка записей
     *
     * @param        $connection Yii::$app->db
     * @param string $tableName  название тавлицы куда будут вставляться данные
     * @param array  $insertArray
     * @param int    $row_insert Кол-во вставляемых данных за раз
     */
    function batch_insert_array($connection, string $tableName, array $insertArray, int $row_insert = 10000)
    {
        for ($i = 0; $i < count($insertArray); $i += $row_insert) {
            $start_index = $i;
            $sql_arr = [];
            $end_index = $i + $row_insert > count($insertArray) ? count($insertArray) : $i + $row_insert;
            for ($j = $start_index; $j < $end_index; $j++) {
                $sql_arr[] = $insertArray[$j];
            }

            //Вставка
            $connection->createCommand()->batchInsert(
                $tableName,
                array_keys($sql_arr[0]),
                $sql_arr
            )->execute();

            $sql_arr = null;
        }
    }
}

if (!function_exists('xml2array')) {
    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @param $xmlObject
     * @param array $out
     * @return array
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     */
    function xml2array ( $xmlObject, $out = array () ): array
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? xml2array ( $node ) : $node;

        return $out;
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($text)
    {
        mb_internal_encoding("UTF-8");
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }
}

/**
 * Function that groups an array of associative arrays by some key.
 **/
if (!function_exists('array_group_by')) {
    /**
     * @param       $key
     * @param array $data
     *
     * @return array
     */
    function array_group_by($key, array $data): array
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }
}

/**
 * Возвращает сумму прописью
 *
 * @param $num
 *
 * @return string
 * @author runcore
 * @uses   morph(...)
 */
function num2str(float $num)
{
    $nul = 'ноль';
    $ten = [
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];
    $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
    $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
    $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
    $unit = [ // Units
        ['копейка', 'копейки', 'копеек', 1],
        ['рубль', 'рубля', 'рублей', 0],
        ['тысяча', 'тысячи', 'тысяч', 1],
        ['миллион', 'миллиона', 'миллионов', 0],
        ['миллиард', 'милиарда', 'миллиардов', 0],
    ];
    //
    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
    $out = [];
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
            if (!intval($v)) {
                continue;
            }
            $uk = sizeof($unit) - $uk - 1; // unit key
            $gender = $unit[$uk][3];
            list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1];        # 1xx-9xx
            if ($i2 > 1) {
                $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
            } # 20-99
            else {
                $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
            } # 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) {
                $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            }
        } //foreach
    } else {
        $out[] = $nul;
    }
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);      // rub
    $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 *
 * @param $n
 * @param $f1
 * @param $f2
 * @param $f5
 *
 * @return mixed
 */
function morph($n, $f1, $f2, $f5)
{
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) {
        return $f5;
    }
    $n = $n % 10;
    if ($n > 1 && $n < 5) {
        return $f2;
    }
    if ($n == 1) {
        return $f1;
    }
    return $f5;
}
