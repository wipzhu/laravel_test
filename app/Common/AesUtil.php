<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/11/12
 * Time: 16:57
 */

namespace App\Common;


class AesUtil
{
    const aes_key = 'wipzhu666'; // 秘钥
    const aes_iv = '1h2g3f4e5f6c7b8a'; // 偏移量

    /**
     * @desc AES 加密
     * @param string $input 要加密的数据
     * @param string $key 秘钥
     * @param string $iv 偏移量
     * @return string
     */
    public static function aesEncrypt($input, $key, $iv = '')
    {
        $method = 'AES-128-CBC'; // method取值范围见 openssl_get_cipher_methods();
        $ret = openssl_encrypt($input, $method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($ret);
    }

    /**
     * @desc AES 解密
     * @param string $input 要解密的数据
     * @param string $key 秘钥
     * @param string $iv 偏移量
     * @return string
     */
    public static function aesDecrypt($input, $key, $iv = '')
    {
        $method = 'AES-128-CBC'; // method取值范围见 openssl_get_cipher_methods();
        $decrypted = openssl_decrypt(base64_decode($input), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

}
