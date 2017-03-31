<?php

use PHPUnit\Framework\TestCase;

class GetStatusTest extends TestCase
{

    public function testGetStatusWithArticlesLeft(){
        json_decode(StatusDriver::getStatus());
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }

}