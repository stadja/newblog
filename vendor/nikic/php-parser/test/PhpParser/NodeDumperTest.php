<?php

namespace PhpParser;

class NodeDumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTestDump
     * @covers NodeDumper::dump
     */
    public function testDump($node, $dump) {
        $dumper = new NodeDumper;

        $this->assertEquals($dump, $dumper->dump($node));
    }

    public function provideTestDump() {
        return array(
            array(
                array(),
'array(
)'
            ),
            array(
                array('Foo', 'Bar', 'Key' => 'FooBar'),
'array(
    0: Foo
    1: Bar
    Key: FooBar
)'
            ),
            array(
                new Node\Name(array('Hallo', 'World')),
'Name(
    parts: array(
        0: Hallo
        1: World
    )
)'
            ),
            array(
                new Node\Expr\Array_(array(
                    new Node\Expr\ArrayItem(new Node\Scalar\String('Foo'))
                )),
'Expr_Array(
    items: array(
        0: Expr_ArrayItem(
            key: null
            value: Scalar_String(
                value: Foo
            )
            byRef: false
        )
    )
)'
            ),
        );
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Can only dump nodes and arrays.
     */
    public function testError() {
        $dumper = new NodeDumper;
        $dumper->dump(new \stdClass);
    }
}