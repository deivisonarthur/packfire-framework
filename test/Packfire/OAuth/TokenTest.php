<?php
namespace Packfire\OAuth;

/**
 * Test class for Token.
 * Generated by PHPUnit on 2012-09-26 at 07:53:58.
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Packfire\OAuth\Token
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Token('token', 'secret');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Packfire\OAuth\Token::key
     */
    public function testKey()
    {
        $this->assertEquals('token', $this->object->key());
    }

    /**
     * @covers \Packfire\OAuth\Token::secret
     */
    public function testSecret()
    {
        $this->assertEquals('secret', $this->object->secret());
    }

    /**
     * @covers \Packfire\OAuth\Token::load
     */
    public function testLoad()
    {
        $request = new Request();
        $request->oauth(OAuth::TOKEN, 'test');
        $request->oauth(OAuth::TOKEN_SECRET, 'secret2');
        $object = Token::load($request);
        $this->assertEquals('test', $object->key());
        $this->assertEquals('secret2', $object->secret());
    }

    /**
     * @covers \Packfire\OAuth\Token::assign
     */
    public function testAssign()
    {
        $request = new Request();
        $this->object->assign($request);
        $this->assertEquals('token', $request->oauth(OAuth::TOKEN));
        $this->assertEquals('secret', $request->oauth(OAuth::TOKEN_SECRET));
    }

    /**
     * @covers \Packfire\OAuth\Token::__toString
     */
    public function testToString()
    {
        $this->assertEquals('token', (string) $this->object);
    }
}
