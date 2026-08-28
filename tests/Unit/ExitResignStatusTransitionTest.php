<?php

namespace Tests\Unit;

use App\ExitResign;
use PHPUnit\Framework\TestCase;

class ExitResignStatusTransitionTest extends TestCase
{
    /** @test */
    public function it_only_allows_the_defined_clearance_status_transitions()
    {
        $resign = new ExitResign;

        $resign->status = 'Cleared';
        $this->assertSame(array('Ongoing Computation', 'For Release'), $resign->allowedNextStatuses());

        $resign->status = 'Ongoing Computation';
        $this->assertSame(array('For Release'), $resign->allowedNextStatuses());

        $resign->status = 'For Release';
        $this->assertSame(array('Released'), $resign->allowedNextStatuses());

        $resign->status = 'Released';
        $this->assertSame(array(), $resign->allowedNextStatuses());
    }
}
