<?php
/**
 * author: rauwang
 * email: hi.rauwang@gmail.com
 * datetime: 2020/7/19 15:28
 * description:
 */
class CalcNumByWeight {

    /**
     * @var array
     */
    private $weights = [];

    /**
     * @var int
     */
    private $num;

    private function __construct() {}

    /**
     * 单例
     * @return CalcNumByWeight
     */
    public static function getInstance() : CalcNumByWeight {
        static $object;
        if (empty($object)) {
            $object = new CalcNumByWeight();
        } return $object;
    }

    /**
     * @param array $weights
     */
    public function setWeights(array $weights) : void {
        $this->weights = $weights;
        arsort($this->weights);
    }

    /**
     * @param int $num
     */
    public function setNum(int $num) : void {
        $this->num = $num;
    }

    /**
     * @return array [数量数组]
     */
    public function exec() : array {
        $random_weights = $this->buildRandomWeights($this->weights);
        $nums = $this->calcNumByWeights($random_weights);
        return $this->NumsFilter($nums);
    }

    /**
     * 生成随机权重数组
     * @param  array  $weights [原权重数组]
     * @return array           [新权重数组]
     */
    private function buildRandomWeights(array $weights) : array {
        $random_weights = [];
        $weight_sum = array_sum($weights);
        foreach ($weights as $idx => $weight) {
            $weight_percent = $weight / $weight_sum; // 权重占比
            // 随机数的目的，是让高权限有一定几率减少占比
            $random_weights[$idx] = mt_rand(0, 100) * $weight_percent;
        }
        return $random_weights;
    }

    /**
     * 根据权重数组计算数量数组
     * @param  array  $weights [权重数组]
     * @return array           [数量数组]
     */
    private function calcNumByWeights(array $weights) : array {
        $nums = [];
        $weight_sum = array_sum($weights);
        foreach ($weights as $idx => $weight) {
            // 根据权重占比，计算应该拿取的数量
            $nums[$idx] = round(($weight/$weight_sum) * $this->num);
        }
        return $nums;
    }

    private function NumsFilter(array $nums) : array {
        $num_sum = array_sum($nums);
        $num = $this->num - $num_sum;
        if ($num < 0) { // 溢出
            $nums = $this->NumsFilterForOverFlow($nums, abs($num));
        } else if ($num > 0) { // 不足
            $nums = $this->NumsFilterForLack($nums, $num);
        }
        return $nums;
    }

    /**
     * 数量数组溢出处理
     * @param array $nums         [数量数组]
     * @param int   $overflow_num [溢出个数]
     */
    private function NumsFilterForOverFlow(array $nums, int $overflow_num) : array {
        foreach ($nums as $idx => $num) {
            if ($num > $overflow_num) {
                $nums[$idx] -= $overflow_num;
                break;
            }
            if ($num > 1) {
                --$nums[$idx];
                if (1 > (--$overflow_num)) break;
            }
        } return $nums;
    }

    /**
     * 数量数组缺少处理
     * @param array $nums     [数量数组]
     * @param int   $lack_num [缺少个数]
     */
    private function NumsFilterForLack(array $nums, int $lack_num) : array {
        foreach ($nums as $idx => $num) {
            if ($num < $lack_num) {
                $r_num = mt_rand(1, $lack_num);
                $nums[$idx] += $r_num;
                if (1 > ($lack_num -= $r_num)) break;
            }
        }
        return $nums;
    }

    /**
     * 根据权重数组和需要获取的数量计算数量数组
     * @param  array  $arr_weight [权重数组]
     * @param  int    $num        [需要获取的数量]
     * @return array              [数量数组]
     */
    public static function calc(array $arr_weight, int $num) : array {
        $countByWeight = self::getInstance();
        $countByWeight->setWeights($arr_weight);
        $countByWeight->setNum($num);
        return $countByWeight->exec();
    }
}