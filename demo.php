<?php

include './CalcNumByWeight.php';

$data_weights = [
    20,
    80,
    30,
    90,
    11
];
$data_arrs = [
    ['aaa1','bbb1','ccc1','ddd1','eee1','fff1','ggg1','hhh1','iii1'],
    ['aaa2','bbb2','ccc2','ddd2','eee2','fff2','ggg2','hhh2','iii2'],
    ['aaa3','bbb3','ccc3','ddd3','eee3','fff3','ggg3','hhh3','iii3'],
    ['aaa4','bbb4','ccc4','ddd4','eee4','fff4','ggg4','hhh4','iii4'],
    ['aaa5','bbb5','ccc5','ddd5','eee5','fff5','ggg5','hhh5','iii5'],
];

$num = 6; // 需要取出的数据数量


$nums = CalcNumByWeight::calc($data_weights, $num);

$results = [];
foreach ($nums as $idx => $num) {
    $len = count($data_arrs[$idx]) - 1;
    $r_idxs = [];
    for (; $num; $num--) {
        
        while(true) { // 防止重复获取
            $r_idx = mt_rand(0, $len);
            if (in_array($r_idx, $r_idxs)) continue;
            $r_idxs[] = $r_idx;
            break;
        }
        
        $results[] = $data_arrs[$idx][$r_idx];
    }
}
print_r($results);

