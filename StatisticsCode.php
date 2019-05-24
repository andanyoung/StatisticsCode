#!/usr/bin/env php
<?php

//php 源文件夹 目标输出文件

//获取命令行参数


//从命令行参数列表中获取选项
$opts = getopt('l:i:s', ['suffix::source::output']);
//var_dump($_SERVER);
//var_dump($opts);
$limit_rawNum         = $opts['l'] ?? 0; //限制统计行数 0或者空没限制，default 0。
$ignore               = $opts['i'] ?? 3; //忽略无效行 0不忽略,1:忽略注释；2：忽略换行、空白行；3：忽略注释、换行、空白行。
$needOutputSourceCode = array_key_exists('s', $opts) ? true : false; //是否要输出源代码
$suffix               = $opts['suffix'] ?? '.php';
$source_dic           = $opts['source'] ?? './';
$output_file          = $opts['output'] ?? './StatisticsCode.txt';

$raw_num = 0;

if ($needOutputSourceCode) {
    file_exists($output_file) && unlink($output_file);
}


for_dir($source_dic);
echo 'codeNum:' . $raw_num . "\n";

//遍历文件
function for_dir($dir)
{
    global $suffix;
    $files = [];
    if (@$handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != ".") {
                if (is_dir($dir . "/" . $file)) {
                    $files[$file] = for_dir($dir . "/" . $file);
                } else {
                    if ($suffix && strpos($file, $suffix) === false) {
                        continue;
                    }
                    $files[] = $file;
                    //  echo $dir . "/" . $file;
                    if (!appendFile($dir . "/" . $file)) {
                        break;
                    }
                }
            }
        }
    }
    closedir($handle);
    return $files;
}


//添加源代码
function appendFile($source_dic)
{
    global $output_file, $limit_rawNum, $raw_num, $ignore;

    $handle = fopen($source_dic, "r");//读取二进制文件时，需要将第二个参数设置成'rb'

    // var_dump($handle);

    while (!feof($handle)) {
        $row = fgets($handle, 1024);
        if (($ignore & 2) && isAnnotation($row)) {//忽略注释
            continue;
        }
        if (($ignore & 1) && isBlank($row)) {//忽略空白
            continue;
        }
        if ($limit_rawNum && $limit_rawNum <= $raw_num) {
            fclose($handle);
            return false;
        }
        writeRaw($output_file, $row);
    }
    writeRaw($output_file, "\n");
    fclose($handle);
    return true;
}

//判断是否是注释
function isBlank($row)
{
    $pattern = '/^[\n\r]+$/';
    return preg_match($pattern, $row);
}

//判断是否是注释
function isAnnotation($row)
{
    $annotations = [
        '//',
        '*',
        '--',
        '/*',
        '*/' . '#'
    ];
    $row         = trim($row) . "\n";
    foreach ($annotations as $annotation) {
        if (strpos($row, $annotation) === 0) {
            return true;
        }
    }

    return false;
}


//向文件末尾添加 内容
function writeRaw($file, $content)
{
    global $raw_num, $needOutputSourceCode;
    $raw_num++;

    return $needOutputSourceCode && file_put_contents($file, $content, FILE_APPEND);
}

