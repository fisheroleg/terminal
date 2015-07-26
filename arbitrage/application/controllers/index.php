<?php

class MyClass
{
    public function foo($a, $b) {
        return $a + $b;
    }
}

class MyClassTest extends PHPUnit_Framework_TestCase
{
    public function testfoo() {
        $o = new MyClass();
        $this->assertEquals(4, $o->foo(2, 2));
    }
}

?>