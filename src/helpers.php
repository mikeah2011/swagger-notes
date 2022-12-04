<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

if (!function_exists('camel_snake')) {
    /**
     * @description 对数组的key进行蛇形&驼峰互相转换
     *
     * @param array|null $array       数组数据源
     * @param string     $caseType    枚举支持的类型：
     *                                缺省为空''   - 自动转换；
     *                                camel  - 转成小驼峰；
     *                                snake  - 转成蛇形
     * @param array      $mapping     映射字段集合
     *                                為了解決[數據庫字段]與[入參字段]的對應關係
     *                                規則為：['數據庫字段column' => '入參paramKey']
     *                                如：['settle_oid' => 'oid'];
     *
     * @return array|null
     */
    function camel_snake(?array $array, string $caseType = '', array $mapping = []): ?array
    {
        $defaultCaseType = $caseType;
        foreach ((array)$array as $k => $v) {
            $tmpK = $mapping[$k] ?? $k;
            is_array($v) && camel_snake($v, $defaultCaseType, $mapping);
            unset($array[$k], $array[$tmpK]);
            $k !== $tmpK && $k = $tmpK;
            // 没有默認的caseType就自动识别是否有 - 或者 _
            empty($defaultCaseType) && $caseType = Str::contains($k, ['-', '_']) ? 'camel' : 'snake';
            $array[Str::{$caseType}($k)] = $v;
        }

        return $array;
    }
}

if (!function_exists('get_type')) {
    /**
     * @description 獲取符合swagger的數據類型
     *
     * @param $value
     *
     * @return string
     */
    function get_type($value): string
    {
        $type = gettype($value);
        $type === 'array' && !isset($value[0]) && $type = 'object';

        return $type === 'NULL' ? 'string' : $type;
    }
}

if (!function_exists('generate_file')) {
    /**
     * @description 生成文件，指定目录的文件不存在就直接创建
     *
     * @param string $directory 指定文件目录
     * @param string $filename  文件名
     * @param string $content   文件内容
     *
     * @return void
     */
    function generate_file(string $directory, string $filename, string $content)
    {
        File::isDirectory($directory) or File::makeDirectory($directory, 0777, true, true);;
        file_put_contents($directory . '/' . $filename, $content);
    }
}

if (!function_exists('get_path')) {
    /**
     * @description 根據命名空間、類名所處的路徑
     *
     * @param string $namespace
     * @param string $class
     *
     * @return string
     */
    function get_path(string $namespace, string $class): string
    {
        return base_path(lcfirst(str_replace('\\', '/', $namespace) . '/' . get_class_name($class)));
    }
}

if (!function_exists('get_class_name')) {
    /**
     * @description 獲取類名
     *
     * @param string   $class
     * @param string[] $search
     *
     * @return string
     */
    function get_class_name(string $class, array $search = ['Controller', 'Service', 'Repository']): string
    {
        return str_replace($search, '', class_basename($class));
    }
}