<?php

define('W', 3);

$r = [
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
            'u2',
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
        'a8' => [
            'a8' => [
                'b7',
            ],
        ],
    ],
    'a10' => [
        
    ],
    'b2',
    'b4',
    'b3',
    'b1',
];

class leaf {
    
    public $value;
    public static $rest = [];
    
    public function __construct($value) {
        $this->value = $value;
    }
}

class tree {
    
    public $items = [];
    
    public static function sortObjects(leaf $a, leaf $b) {
        $a = preg_replace("/[^0-9]/", '', $a->value);
        $b = preg_replace("/[^0-9]/", '', $b->value);
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    public function getLeafsAndNodes()
    {
        $nodes = [];
        $leafs = [];
        foreach ($this->items as $item) {
            if ($item instanceof tree) {
                $nodes[] = $item;
            } else if ($item instanceof leaf) {
                $leafs[] = $item;
            }
        }
        return ['nodes' => $nodes, 'leafs' => $leafs];
    }
    
    public function getRestLeafsAfterSummation() {
        $sum = 0;
        $leafsRemain = [];
        $leafs = [];
        foreach ($this->getLeafsAndNodes()['leafs'] as $item) {
            $sum = $sum + (int) preg_replace("/[^0-9]/", '', $item->value);
            if ($sum <= W) {
                $leafsRemain[] = $item;
                continue;
            }
            $leafs[] = $item;
        }
        $this->setLeafs($leafsRemain);
        return $leafs;
    }
    
    public function setLeafs($leafs) {
        $this->items = array_merge($this->getLeafsAndNodes()['nodes'], $leafs);
    }
    
    public function sortLeafs() {
        $nodes = [];
        $leafs = [];
        $leafsAndNodes = $this->getLeafsAndNodes();
        usort($leafsAndNodes['leafs'], [__CLASS__, "sortObjects"]);
        $this->items = array_merge($leafsAndNodes['nodes'], $leafsAndNodes['leafs']);
    }
    
    public static function makeTree($arr)
    {
        $data = new self();
        if (is_array($arr)) {
            if (count($arr) > 0) {
                foreach ($arr as $key => $row) {
                    if (is_array($row)) {
                        $data->items[] = self::makeTree($row);
                    } else if (is_scalar($row)) {
                        $data->items[] = new leaf($row);
                    }
                }
            }
        }
        
        return $data;
    }
    
    public static function leafsRecursive (tree $obj) {
        $leafs = [];
        $nodes = [];
        foreach ($obj->items as $item) {
            if ($item instanceof tree) {
                $nodes[] = self::leafsRecursive($item);
            }
        }
        $obj->setLeafs(array_merge($obj->getLeafsAndNodes()['leafs'], leaf::$rest));
        $obj->sortLeafs();
        
        $restLeafsAfterSummation = $obj->getRestLeafsAfterSummation();
        
        leaf::$rest = $restLeafsAfterSummation;
        $obj->items = array_merge($nodes, $obj->getLeafsAndNodes()['leafs']);
        
        return $obj;
    }
}

echo '<pre>';
print_r(tree::LeafsRecursive(tree::makeTree($r)));
echo '</pre>';
