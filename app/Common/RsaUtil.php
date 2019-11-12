<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/11/11
 * Time: 15:34
 */

namespace App\Common;


class RsaUtil
{
    const privateKeyFilePath = __DIR__ . '/../../../config/key_file/private_key.pem';
    const publicKeyFilePath = __DIR__ . '/../../../config/key_file/public_key.pem';

    /**
     * @desc 生成密钥对
     * @param int $keyBits 秘钥位数
     * @param int $keyType 秘钥类型 https://www.php.net/manual/zh/openssl.key-types.php
     *      $keyType取值:OPENSSL_KEYTYPE_RSA, OPENSSL_KEYTYPE_DSA, OPENSSL_KEYTYPE_DH, OPENSSL_KEYTYPE_EC
     * @return bool
     */
    public static function generateKeyPair($keyBits = 1024, $keyType = OPENSSL_KEYTYPE_RSA)
    {
        try {
            // openssl_pkey_new()参数配置：https://www.php.net/manual/zh/function.openssl-pkey-new.php
            $new_key_pair = openssl_pkey_new([
                "private_key_bits" => $keyBits,
                "private_key_type" => $keyType,
            ]);
            openssl_pkey_export($new_key_pair, $private_key_pem);
            echo $private_key_pem;

            $details = openssl_pkey_get_details($new_key_pair);
            $public_key_pem = $details['key'];
            echo $public_key_pem;

            //save key pair
            file_put_contents(__DIR__ . '/../../../config/key_file/private_key.pem', $private_key_pem);
            file_put_contents(__DIR__ . '/../../../config/key_file/public_key.pem', $public_key_pem);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * RSA生成签名
     * 生成时使用SHA256算法加密
     * @param string $data 签名材料
     * @param string $code 签名编码方式 base64|hex|bin
     * @param int $padding 填充方式
     *      $padding取值： https://www.php.net/manual/zh/openssl.signature-algos.php
     * @return bool|string 签名值
     */
    public static function rsaSign($data, $code = 'base64', $padding = OPENSSL_ALGO_SHA256)
    {
        //读取私钥文件
        $priKey = file_get_contents(self::privateKeyFilePath);
        //转换为openssl格式密钥
        $res = openssl_get_privatekey($priKey);

        $sign = false;
        if (openssl_sign($data, $sign, $res, $padding)) {
            // 编码后返回
            $sign = self::_encode($sign, $code);
        }
        return $sign;
    }

    /**
     * RSA验证签名
     * 验证时使用SHA256算法解密
     * @param string $data 签名材料
     * @param string $sign 签名值
     * @param string $code 签名编码方式 base64|hex|bin
     * @param int $padding 填充方式
     *      $padding取值： https://www.php.net/manual/zh/openssl.signature-algos.php
     * @return bool
     */
    public static function rsaVerify($data, $sign, $code = 'base64', $padding = OPENSSL_ALGO_SHA256)
    {
        //读取公钥文件
        $pubKey = file_get_contents(self::publicKeyFilePath);
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
        $ret = false;

        // 先将签名串进行解码处理
        $sign = self::_decode($sign, $code);
        // 再验证签名
        if ($sign !== false) {
            switch (openssl_verify($data, $sign, $res, $padding)) {
                case 1:
                    $ret = true;
                    break;
                case 0:
                case -1:
                default:
                    $ret = false;
            }
        }
        return $ret;
    }

    /**
     * @desc RSA加密
     * @param $data string 明文字符串
     * @param string $code 密文编码方式 base64|hex|bin
     * @param int $padding 填充方式
     *      $padding取值：OPENSSL_PKCS1_PADDING, OPENSSL_SSLV23_PADDING,
     *                  OPENSSL_PKCS1_OAEP_PADDING, OPENSSL_NO_PADDING
     * @return bool|string
     */
    public static function rsaEncrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING)
    {
        //读取公钥文件
        $pubKey = file_get_contents(self::publicKeyFilePath);
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        $encrypted = false;
        if (openssl_public_encrypt($data, $encrypted, $res, $padding)) {
            // 编码后返回
            $encrypted = self::_encode($encrypted, $code);
        }
        return $encrypted;
    }

    /**
     * @desc RSA解密
     * @param $data string 要解密的密文字符串
     * @param string $code 密文编码方式  base64|hex|bin
     * @param int $padding 填充方式
     *      $padding取值：OPENSSL_PKCS1_PADDING, OPENSSL_SSLV23_PADDING,
     *                  OPENSSL_PKCS1_OAEP_PADDING, OPENSSL_NO_PADDING
     * @param bool $rev 是否将明文串反转
     * @return bool|string
     */
    public static function rsaDecrypt($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false)
    {
        //读取私钥钥文件
        $priKey = file_get_contents(self::privateKeyFilePath);
        //转换为openssl格式密钥
        $res = openssl_get_privatekey($priKey);
        $decrypted = false;

        // 先将密文进行解码处理
        $data = self::_decode($data, $code);
        if ($data !== false) {
            if (openssl_private_decrypt($data, $decrypted, $res, $padding)) {
                $decrypted = $rev ? rtrim(strrev($decrypted), "\0") : '' . $decrypted;
            }
        }
        return $decrypted;
    }

    /**
     * @desc 获取毫秒级时间戳
     * @return string
     */
    public static function getMillionTimestamp()
    {
        list($microTime, $time) = explode(' ', microtime());
        $millionTimestamp = round(($microTime + $time) * 1000, 0);
        return $millionTimestamp;
    }

    /**
     * @desc 组装加签/验签前的字符串
     * @param array $data 要转成字符串的数组
     * @param string $signTime 加签时间戳(因精确到毫秒级,实时生成会有误差,故当做形参传入)
     * @param bool $needEncode 是否需要url转码
     * @return string 格式如 1540537491925|00||||||||U01|qgg250154053749192499921||||4127261994
     */
    public static function getStrToBeSigned($data, $signTime, $needEncode = false)
    {
        $data['signTime'] = $signTime;
        // 按key升序排列
        ksort($data);
        // http_build_query：使用给出的关联（或下标）数组生成一个经过 URL-encode 的请求字符串。
        $strToBeSigned = http_build_query($data);
        if (!$needEncode) {
            $strToBeSigned = urldecode($strToBeSigned);
        }
        return $strToBeSigned;
    }

    /**
     * @desc 转码
     * @param $data
     * @param $code
     * @return bool|string
     */
    private static function _encode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64' :
                $res = base64_encode($data);
                break;
            case 'hex':
                $res = bin2hex($data);
                break;
            case 'bin':
            default:
                $res = false;
        }
        return $res;
    }

    /**
     * @desc 解码
     * @param $data
     * @param $code
     * @return bool|string
     */
    private static function _decode($data, $code)
    {
        switch (strtolower($code)) {
            case 'base64':
                $res = base64_decode($data);
                break;
            case 'hex':
                $res = hex2bin($data);
                break;
            case 'bin':
            default:
                $res = false;
        }
        return $res;
    }

}
