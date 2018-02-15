<?php

namespace Test\Datamolino;

use Datamolino\Token;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2018-02-15 at 17:03:07.
 */
class TokenTest extends ApiClientTest
{
    /**
     * @var Token
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Token();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Datamolino\Token::setUp
     */
    public function testSetUp()
    {
        $this->object->setUp();
    }

    /**
     * @covers Datamolino\Token::authentication
     */
    public function testAuthentication()
    {
        $this->object->authentication();
    }

    /**
     * @covers Datamolino\Token::getTokenString
     */
    public function testGetTokenString()
    {
        $this->object->getTokenString();
    }

    /**
     * @covers Datamolino\Token::takeData
     */
    public function testTakeData()
    {
        $this->object->takeData(['expires_in' => 7200]);
    }

    /**
     * @covers Datamolino\Token::isTokenExpired
     */
    public function testIsTokenExpired()
    {
        $this->object->isTokenExpired();
    }

    /**
     * @covers Datamolino\Token::requestFreshToken
     */
    public function testRequestFreshToken()
    {
        $this->object->requestFreshToken();
    }

    /**
     * @covers Datamolino\Token::refreshToken
     */
    public function testRefreshToken()
    {
        $this->object->refreshToken();
    }

    /**
     * @covers Datamolino\Token::singleton
     */
    public function testSingleton()
    {
        Token::singleton();
    }

    /**
     * @covers Datamolino\Token::instanced
     */
    public function testInstanced()
    {
        Token::instanced();
    }
}