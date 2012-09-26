<?php

pload('packfire.oauth.pOAuthSignature');

/**
 * Test class for pOAuthSignature.
 * Generated by PHPUnit on 2012-09-26 at 08:04:17.
 */
class pOAuthSignatureTest extends PHPUnit_Framework_TestCase {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pOAuthSignature::load
     */
    public function testLoad() {
        $class = pOAuthSignature::load('HMAC-SHA1');
        $this->assertEquals('pOAuthHmacSha1Signature', $class);
    }

    /**
     * @covers pOAuthSignature::load
     */
    public function testLoad2() {
        $class = pOAuthSignature::load('PLAINTEXT');
        $this->assertEquals('pOAuthPlainTextSignature', $class);
    }

    /**
     * @covers pOAuthSignature::check
     */
    public function testCheck() {
        $stub = $this->getMockForAbstractClass('pOAuthSignature', array(), '', false);
        $stub->expects($this->exactly(2))
                ->method('build')
                ->will($this->returnValue('test'));
        $this->assertTrue($stub->check('test'));
        $this->assertFalse($stub->check('test2'));
    }

}