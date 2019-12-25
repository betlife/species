<?php

namespace Artisan\Security;

/**
 * Description of Openssl
 *
 * @author Cloud
 */
use RuntimeException;
use Exception;
use Artisan\Contracts\Openssl\Openssl as iOpenssl;

class Openssl implements iOpenssl
{

    const OPENSSL_METHOD = 'aes-256-cbc';
    /**
     *
     * @var string
     */
    protected $key;
    /**
     *
     * @var string
     */
    protected $salt;
    /**
     *
     * @var type 
     */
    protected $cipher = 'aes-256-cbc';
    /**
     * 
     * @param type $key
     * @param type $salt
     * @param type $cipher
     */
    public function __construct($key = null, $salt = null, $cipher = null)
    {
        $this->key = $key;
        $this->salt= $salt;
        $this->cipher = $cipher ?? $this->cipher;
        $this->check($this->cipher);
       
    }
    /**
     * 
     * @param type $string
     * @return type
     */
    public function encipherString($string)
    {
        return $this->encipher($string, $this->key, $this->salt);
    }
    /**
     * 
     * @param type $chunk
     * @return type
     */
    public function decipherString($chunk)
    {
        return $this->decipher($chunk, $this->key, $this->salt);
    }
    /**
     * 
     * @param string $plain
     * @param string $key
     * @param string|null $salt
     * @return $this
     * @throws RuntimeException
     */
    public function encipher($plain, $key, $salt = null)
    {
        $iv = static::iv();

        $password = static::generateKey($key . $iv, $salt, static::OPENSSL_METHOD);
        $cipher   = openssl_encrypt($plain, static::OPENSSL_METHOD, $password, 0, $iv);
        $hmac     = Hash::hmac($cipher, $password);
        if( $error = openssl_error_string() ) {
            throw new RuntimeException(sprintf('Encryption error help text: %s', is_array($error) ? implode('|', $error) : $error));
        }

        //return base64_encode($iv . $cipher . $hmac);
        return static::initializeJsonDataAndEncode(compact('iv', 'cipher', 'hmac'));
    }

    /**
     * 
     * @param string $cipher
     * @param string $key
     * @param string|null $salt
     * @return $this
     * @throws RuntimeException
     */
    public function decipher($cipher, $key, $salt = null)
    {
        $chunk     = static::getJsonData($cipher);
        $password  = static::generateKey($key . $chunk->iv, $salt, static::OPENSSL_METHOD);
        
        $checkHmac = Hash::equals($chunk->hmac, function(Hash $app) use ($chunk, $password) {
                    return $app->hmac($chunk->cipher, $password);
                });
        if( !$checkHmac ) {
            throw new RuntimeException('Invalid MAC data supplied');
        }
        return openssl_decrypt($chunk->cipher, static::OPENSSL_METHOD, $password, 0, $chunk->iv);
        
    }

    /**
     * IV length of corresponding cipher algorithm
     * 
     * @return int
     */
    public static function length()
    {
        return openssl_cipher_iv_length(static::OPENSSL_METHOD);
    }

    /**
     * Generates an IV for encryption and decryption
     * @return string
     */
    protected static function iv()
    {
        return Security::randomHash(static::length());
    }

    /**
     * 
     * @param string $key
     * @param string $salt
     * @return string
     */
    protected static function generateKey($key, $salt, $cipher = 'aes-256-cbc')
    {
        return openssl_pbkdf2($key, $salt, $cipher === 'aes-256-cbc' ? 32 : 16, 10000);
    }

    /**
     * 
     * @param string $json
     * @return json
     * @throws RuntimeException
     */
    protected static function getJsonData($json)
    {
        $chunk = json_decode(base64_decode($json));
        if( !static::validateJsonData($chunk) ) {
            throw new RuntimeException('Invalid cipher');
        }

        return $chunk;
    }

    /**
     * Validates cipher's JSON's data
     * @param json $json JSON object
     * @return bool
     */
    protected static function validateJsonData($json)
    {
        return is_object($json) && isset($json->hmac, $json->cipher, $json->iv) && mb_strlen($json->iv, '8bit') === static::length();
    }

    /**
     * 
     * @param string $data
     * @return string
     * @throws RuntimeException
     */
    protected static function initializeJsonDataAndEncode($data)
    {
        $json = json_encode($data);
        if( json_last_error() !== JSON_ERROR_NONE ) {
            throw new RuntimeException(json_last_error_msg());
        }

        return base64_encode($json);
    }
    /**
     * 
     * @param type $method
     * @throws Exception
     */
    protected function check($method)
    {
        if(!in_array($method, static::supportedCiphers())){
            throw new Exception();
        }
    }
    /**
     * 
     * @return array
     */
    protected static function supportedCiphers()
    {
        return ['aes-256-cbc', 'aes-128-cbc'];
    }

}
