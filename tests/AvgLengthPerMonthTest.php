<?php

namespace SmCrunch;

use PHPUnit\Framework\TestCase;
use SmCrunch\Processors\AvgLengthPerMonth;

class AvgLengthPerMonthTest extends TestCase {

    public function testCalculation() {
        $p = new AvgLengthPerMonth();

        $p->process( $this->getBatch( 1 ) );
        $this->assertEquals( [ '202001' => 2 ], $p->report() );

        $p->process( $this->getBatch( 2 ) );
        $this->assertEquals( [ '202001' => 2, '202002' => 4 ], $p->report() );

        $p->process( $this->getBatch( 3 ) );
        $this->assertEquals( [ '202001' => 2, '202002' => 4, '202003' => 6 ], $p->report() );

        $p->process( $this->getSpreadBatch() );
        $this->assertEquals( [ '202001' => 1.75, '202002' => 3.5, '202003' => 5.25 ], $p->report() );
    }

    protected function getBatch( $offset = 1 ) {
        $month = str_pad( (string)$offset, 2, '0', STR_PAD_LEFT );

        $p1 = new Post();
        $p1->message = str_repeat( 'z', $offset );
        $p1->createdAt = new \DateTimeImmutable( "2020-{$month}-01T22:05:58+00:00" );

        $p2 = new Post();
        $p2->message = str_repeat( 'z', $offset * 2 );
        $p2->createdAt = new \DateTimeImmutable( "2020-{$month}-02T22:05:58+00:00" );

        $p3 = new Post();
        $p3->message = str_repeat( 'z', $offset * 3 );
        $p3->createdAt = new \DateTimeImmutable( "2020-{$month}-03T22:05:58+00:00" );

        return [ $p1, $p2, $p3 ];
    }

    protected function getSpreadBatch( $offset = 1 ) {

        $p1 = new Post();
        $p1->message = str_repeat( 'z', $offset );
        $month = str_pad( (string)$offset, 2, '0', STR_PAD_LEFT );
        $p1->createdAt = new \DateTimeImmutable( "2020-{$month}-01T22:05:58+00:00" );

        $p2 = new Post();
        $p2->message = str_repeat( 'z', $offset * 2 );
        $month = str_pad( (string)( $offset + 1 ), 2, '0', STR_PAD_LEFT );
        $p2->createdAt = new \DateTimeImmutable( "2020-{$month}-02T22:05:58+00:00" );

        $p3 = new Post();
        $p3->message = str_repeat( 'z', $offset * 3 );
        $month = str_pad( (string)( $offset + 2 ), 2, '0', STR_PAD_LEFT );
        $p3->createdAt = new \DateTimeImmutable( "2020-{$month}-03T22:05:58+00:00" );

        return [ $p1, $p2, $p3 ];
    }

}
