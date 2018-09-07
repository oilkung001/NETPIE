<?php
/**
 * PHP Unit Test Case
 */

use Pentagonal\Phpass\PasswordHash;

class OnlyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Constant
     */
    const HASH_PREFIX = '$2a$';
    const HASH_PORTABLE_PREFIX = '$P$';
    const STORED_PLAIN_VALID = 'string';
    const STORED_PLAIN_INVALID = 'invalid';
    const STORED_HASH = '$2a$08$mNxdFMzB2GeCrotHAgviA.3OjkRMtm2b6c5i/Byx6JJqJwB6liJEG';
    const STORED_PORTABLE = '$P$B9ONMngc6ZoLbAyfpsTnxP1gxs7atD1';

    public function testCorrectHashWithCreate()
    {
        $hasher  = new PasswordHash(8, false);
        $hash    = $hasher->HashPassword(self::STORED_PLAIN_VALID);
        /**
         * Asserting true
         */
        $this->assertTrue(
            $hasher->checkPassword(self::STORED_PLAIN_VALID, $hash)
        );
    }

    public function testCorrectHashWithStored()
    {
        $hasher  = new PasswordHash(8, false);
        /**
         * Asserting true
         */
        $this->assertTrue(
            $hasher->checkPassword(self::STORED_PLAIN_VALID, self::STORED_HASH)
        );
    }

    public function testIncorrectHashWithCreate()
    {
        $hasher  = new PasswordHash(8, false);
        $hash    = $hasher->HashPassword(self::STORED_PLAIN_VALID);
        /**
         * Asserting false
         */
        $this->assertFalse(
            $hasher->checkPassword(self::STORED_PLAIN_INVALID, $hash)
        );
    }

    public function testIncorrectHashWithStored()
    {
        $hasher  = new PasswordHash(8, false);
        /**
         * Asserting false
         */
        $this->assertFalse(
            $hasher->checkPassword(self::STORED_PLAIN_INVALID, self::STORED_HASH)
        );
    }

    public function testCorrectPortableHashWithCreate()
    {
        $hasher  = new PasswordHash(8, true);
        $hash    = $hasher->HashPassword(self::STORED_PLAIN_VALID);
        /**
         * Asserting true
         */
        $this->assertTrue(
            $hasher->checkPassword(self::STORED_PLAIN_VALID, $hash)
        );
    }

    public function testCorrectPortableHashWithStored()
    {
        $hasher  = new PasswordHash(8, true);
        /**
         * Asserting true
         */
        $this->assertTrue(
            $hasher->checkPassword(self::STORED_PLAIN_VALID, self::STORED_PORTABLE)
        );
    }

    public function testIncorrectPortableHashWithCreate()
    {
        $hasher  = new PasswordHash(8, true);
        $hash    = $hasher->HashPassword(self::STORED_PLAIN_VALID);
        /**
         * Asserting false
         */
        $this->assertFalse(
            $hasher->checkPassword(self::STORED_PLAIN_INVALID, $hash)
        );
    }

    public function testIncorrectPortableHashWithStored()
    {
        $hasher  = new PasswordHash(8, true);
        /**
         * Asserting false
         */
        $this->assertFalse(
            $hasher->checkPassword(self::STORED_PLAIN_INVALID, self::STORED_PORTABLE)
        );
    }

    public function testContainsAndStartHash()
    {
        $hasher  = new PasswordHash(8, false);
        $hasherPortable  = new PasswordHash(8, true);

        $hash = $hasher->HashPassword(self::STORED_PLAIN_VALID);
        $hashPortable = $hasherPortable->HashPassword(self::STORED_PLAIN_VALID);

        $this->assertContains(self::HASH_PREFIX, $hash);
        $this->assertContains(self::HASH_PORTABLE_PREFIX, $hashPortable);
        $this->assertStringStartsWith(self::HASH_PREFIX, $hash);
        $this->assertStringStartsWith(self::HASH_PORTABLE_PREFIX, $hashPortable);
    }
}
