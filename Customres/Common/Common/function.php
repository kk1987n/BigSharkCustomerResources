<?php

/**
 * 生成随机字符串
 * @param type $length 生成长度
 * @param type $Big 是否包含大写字母
 * @param type $Num 是否包含数字
 * @param type $lower 是否包含小写字母
 * @param type $s 是否包含符号
 * @return type 生成的字符串
 */
function getRandChar($length, $Big = true, $Num = true, $lower = true, $s = false) {
    $str = null;
    $strPol .= $Big ? "ABCDEFGHIJKLMNOPQRSTUVWXYZ" : '';
    $strPol .= $Num ? "0123456789" : '';
    $strPol .= $lower ? "abcdefghijklmnopqrstuvwxyz" : '';
    $strPol .= $s ? "~!@#$%^&*()_+|<>?:;,.=-" : '';




    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}
