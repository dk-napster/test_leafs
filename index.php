<?php

define(W, 3);

$tastArr = [
    'a1' => [
        'a4' => [
            'd2'
        ],
        'c8',
        'c7',
        'f2',
        
    ],
    'a2' => [
        'c2',
        'k1',
        'a5' => [
            'e2',
        ]
    ],
    'a3' => [
        'c2',
        'c1',
    ],
    'a6' => [
        'a7' => [
            
        ],
        'c2',
        'c1',
    ],
    'a8' => [
        
    ],
    'b2',
    'b4',
    'b3',
    'b1'
];

function LeafsRecursive ($arrayToProcess) {
    static $restLeafs = [];
    
    $arrays = [];
    $leafs = [];
    foreach ($arrayToProcess as $key => $val) {
        if (is_array($val)) {
            $arrays[$key] = $val;
        } else if (is_string($val)) {
            $valNumeric = preg_replace("/[^0-9]/", '', $val);
            $leafs[$valNumeric] = $val;
        }
    }
    
    foreach ($restLeafs as $key => $restLeaf) {
        $valNumeric = preg_replace("/[^0-9]/", '', $restLeaf);
        $leafs[$valNumeric] = $restLeaf;
    }
    
    if (!empty($arrays)) {
        ksort($arrays);
        reset($arrays);
    }
    ksort($leafs);
    
    $res = 0;
    
    $leafsRemain = $leafs;
    $restLeafs = [];
    if (array_sum(array_flip($leafs)) > W) {
        $leafsRemain = [];
        foreach ($leafs as $key => $leaf) {
            if ($res = $res + $key <= W) {
                $leafsRemain[] = $leaf;
                continue;
            }
            
            if (!empty($arrays)) {
                $arrays[key($arrays)][] = $leaf;
            } else {
                $restLeafs[] = $leaf;
            }
        }
    }
    
    if (!empty($arrays)) {
        foreach ($arrays as $key => $row) {
            $arrays[$key] = LeafsRecursive($row);
        }
    }
    $output = array_merge($arrays, $leafsRemain);
    return $output;
}

echo '<pre>';
print_r(LeafsRecursive($tastArr));
echo '</pre>';
