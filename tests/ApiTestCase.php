<?php

class ApiTestCase extends TestCase
{


    public function assertSetEquals($expected, $actual)
    {
        $this->assertEquals($expected, $actual, "", 0.0, 10, true);
    }
}
