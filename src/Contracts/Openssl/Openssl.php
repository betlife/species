<?php

namespace Artisan\Contracts\Openssl;

/**
 *
 * @author Cloud
 */
interface Openssl
{
    /**
     * Encrypts a plaintext message
     * 
     * @param string $plain
     * @param string $key
     * @param string $salt
     */
    public function encipher($plain, $key, $salt = null);
    /**
     * Decrypts a ciphertext message
     * 
     * @param string $cipher
     * @param string $key
     * @param string $salt
     */
    public function decipher($cipher, $key, $salt = null);
}
