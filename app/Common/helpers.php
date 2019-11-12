<?php


if (!function_exists('pr')) {
    /**
     * @desc 格式化输出调试信息
     * @param $arr
     * @author wipzhu
     * @update unknown
     */
    function pr($arr)
    {
        if (is_array($arr) || is_object($arr)) {
            if (!empty($arr)) {
                echo "<pre>";
                print_r($arr);
                echo "<pre/>";
            } else {
                echo "pr数组为空";
            }
        } else {
            echo "<pre>";
            var_dump($arr);
            echo "<pre/>";
        }
    }
}

if (!function_exists('array_deep')) {
    /**
     * @desc 判断数组的维数
     * @param $arr
     * @param $al
     * @param int $level
     * @author wipzhu
     * @update unknown
     */
    function aL($arr, &$al, $level = 0)
    {
        if (is_array($arr)) {
            $level++;
            $al[] = $level;
            foreach ($arr as $v) {
                aL($v, $al, $level);
            }
        }
    }

    function array_deep($arr)
    {
        $al = array(0);
        aL($arr, $al);
        return max($al);
    }
}

if (!function_exists('array_rekey')) {
    /**
     * @desc 以  索引数组的某个键的值  重建二维索引数组为关联数组
     * @param $arr
     * @param $str
     * @return string
     * @author wipzhu
     * @update unknown
     */
    function array_re_key($arr, $str)
    {
        if (2 != array_deep($arr)) {
            return '输入的数组不是2维数组!';
        }
        foreach ($arr as $val) {
            $arr_rekey["{$val["$str"]}"] = $val;
        }
        return $arr_rekey;
    }
}

if (!function_exists('getBlurStr')) {
    /**
     * @desc 格式化字串——隐藏字串部分信息
     * @param $string
     * @param int $start
     * @param int $repeatNum
     * @param string $repeatFlag
     * @return mixed
     */
    function getBlurStr($string, $start = 1, $repeatNum = 1, $repeatFlag = '*')
    {
        if ($repeatNum <= 0) {
            return $string;
        }
        $repeatFlag = $repeatFlag ? $repeatFlag : '*';
        $repeat = str_repeat($repeatFlag, $repeatNum);
        return substr_replace($string, $repeat, $start, $repeatNum);
    }
}

if (!function_exists('getBlurMobile')) {
    /**
     * @desc 格式化字串——隐藏手机号部分信息
     * @param $num
     * @return mixed
     */
    function getBlurMobile($num)
    {
        return preg_replace('/^(\d{3})\d{4}(\d{4})/', '${1}****${2}', $num);
    }
}

if (!function_exists('toCNcap')) {
    /**
     * 货币转大写中文
     * @param number $data
     * @return string
     */
    function toCNcap($data)
    {
        $capnum = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖");
        $capdigit = array("", "拾", "佰", "仟");
        $subdata = explode(".", $data);
        $yuan = $subdata[0];
        $j = 0;
        $nonzero = 0;
        for ($i = 0; $i < strlen($subdata[0]); $i++) {
            if (0 == $i) { //确定个位
                if ($subdata[1]) {
                    $cncap = (substr($subdata[0], -1, 1) != 0) ? "元" : "元零";
                } else {
                    $cncap = "元";
                }
            }
            if (4 == $i) {
                $j = 0;
                $nonzero = 0;
                $cncap = "万" . $cncap;
            } //确定万位
            if (8 == $i) {
                $j = 0;
                $nonzero = 0;
                $cncap = "亿" . $cncap;
            } //确定亿位
            $numb = substr($yuan, -1, 1); //截取尾数
            $cncap = ($numb) ? $capnum[$numb] . $capdigit[$j] . $cncap : (($nonzero) ? "零" . $cncap : $cncap);
            $nonzero = ($numb) ? 1 : $nonzero;
            $yuan = substr($yuan, 0, strlen($yuan) - 1); //截去尾数
            $j++;
        }
        if ($subdata[1]) {
            $chiao = (substr($subdata[1], 0, 1)) ? $capnum[substr($subdata[1], 0, 1)] . "角" : "零";
            $cent = (substr($subdata[1], 1, 1)) ? $capnum[substr($subdata[1], 1, 1)] . "分" : "零分";
        }
        $cncap .= $chiao . $cent . "整";
        $cncap = preg_replace("/(零)+/", "\\1", $cncap); //合并连续“零”
        return $cncap;
    }
}

if (!function_exists('generate_pdf')) {
    /**
     * 生成pdf，提供在线浏览和下载两种方式
     * @param string $html
     * @param string $filename
     * @param string $exec
     * @param string $download
     */
    function generate_pdf($html, $filename = 'download.pdf', $exec = false, $download = false)
    {
        $descriptorspec = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'w'), // stderr
        );
        $process = proc_open('xvfb-run --server-args="-screen 0, 1024x680x24" wkhtmltopdf --use-xserver ' . $html . ' -', $descriptorspec, $pipes);
        $pdf = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        proc_close($process);
        if ($errors) die('PDF_GENERATOR_ERROR:<br />' . nl2br(htmlspecialchars($errors)));
        header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Length: ' . strlen($pdf));
        if ($download === true) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream', false);
            header('Content-Type: application/download', false);
            header('Content-Type: application/pdf', false);
            header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
            header('Content-Transfer-Encoding: binary');
        } else {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($filename) . '";');
        }
        echo $pdf;
        die;
    }

    if (!function_exists('parseJson')) {
        /**
         * 返回json封装
         * @param $data
         * @return string
         */
        function parseJson($data)
        {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
}
