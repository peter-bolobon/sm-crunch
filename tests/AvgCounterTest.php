<?php

namespace SmCrunch;

use PHPUnit\Framework\TestCase;

class AvgCounterTest extends TestCase {

    public function testIntegers() {
        $counter = new AvgCounter();
        $values = [ 1, 2, 3, 4, 5 ];
        $average = 3;

        foreach ( $values as $value ) {
            $counter->add( $value );
        }

        $this->assertEquals( $average, $counter->average, 'Count an average of 5 integers' );
    }

    public function testSame() {
        $counter = new AvgCounter();
        $values = array_fill( 0, 42, 42 );
        $average = 42;

        foreach ( $values as $value ) {
            $counter->add( $value );
        }

        $this->assertEquals( $average, $counter->average, 'Count an average of 42 42s' );
    }

    public function testFloats() {
        $counter = new AvgCounter();
        $values = [ 1.5, 3, 4.5, 6, 7.5, 9 ];
        $average = 5.25;

        foreach ( $values as $value ) {
            $counter->add( $value );
        }

        $this->assertEquals( $average, $counter->average, 'Count an average of 6 floats' );
    }

}
