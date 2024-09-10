<?php

class Calculate
{
    /**
     * 计算逻辑：
     * 1. 把字符串 转换成 运算数组 eg: '10 + 10 - 10 * 2'  =====>  [10,'+',10,'-',10,'*','2']
     * 2. 把运算数组 按乘除的优先顺序，优先处理   [10,'+',10,'-',10,'*','2']   =====>  [10,'+',10,'-',20]
     * 3. 把运算数组 按加减顺序去计算处理  [10,'+',10,'-',20]  ===>  [ 20,'-',20]  === > [ 0 ] ===> 最终结果等于 0
     *
     */
    public function calculate()
    {
        $str = '100+100+100-100*   7  -2';
        $str = '1+1 -10';
        $str = '1+1 -10.99 +8 /8 *1';

        $arr = [];
        $numberBuffer = ''; // 临时字符串
        for ($i = 0; $i < strlen($str); $i++) {
            $char = $str[$i];
            if (is_numeric($char) || $char === '.') {
                $numberBuffer .= $char;
            } elseif ($char !== ' ') {
                if ($numberBuffer !== '') {
                    $arr[] = $numberBuffer; // 将临时字符串添加到数组
                    $numberBuffer = ''; // 重置临时字符串
                }
                $arr[] = $char; // 操作符加入到数组
            }
        }
        // 判断是否还有剩余数字
        if ($numberBuffer !== '') {
            $arr[] = $numberBuffer;
        }



        //这里优先把处理 * 和 / 的运算符，处理（从左到右）
        for ($j=0;$j<count($arr);$j++){
            foreach ($arr as $k=>$item){
                if($arr[$k] == '*' || $arr[$k] == '/'){
                    $op = $arr[$k];
                    $arr[$k] = $this->opCount($op,$arr[$k-1],$arr[$k+1]);       //找到操作符，执行操作符运算
                    //操作符操作过后，删除操作符两边的数字，以及索引
                    unset($arr[$k-1]);
                    unset($arr[$k+1]);
                    break;
                }
            }
            $arr = array_values($arr);  //重新整理数组索引
        }



        //这里继续从左到右处理 + 和 - 的运算数组 (原理同上)
        for ($l=0;$l<count($arr)+1;$l++){
            foreach ($arr as $k=>$item){
                if($arr[$k] == '+' || $arr[$k] == '-'){
                    $op = $arr[$k];
                    $arr[$k] = $this->opCount($op,$arr[$k-1],$arr[$k+1]);
                    unset($arr[$k-1]);
                    unset($arr[$k+1]);
                    break;
                }
            }
            $arr = array_values($arr);
        }

        echo $str.'=' . $arr[0];
        dd($arr);
    }

    /**
     * @param $op string 操作符
     * @param $val1 int 操作符左边的数字
     * @param $val2 int 操作符右边的数字
     *
     */
    function opCount($op, $val1, $val2) {
        switch ($op) {
            case '+': return $val1 + $val2;
            case '-': return $val1 - $val2;
            case '*': return $val1 * $val2;
            case '/': return $val1 / $val2;
        }
    }



}
