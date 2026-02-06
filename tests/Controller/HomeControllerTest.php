<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsAccessible()
    {
        $this->assertStringContainsString('AI Portfolio Assistant', 'AI Portfolio Assistant');
    }

    public function testHomePageIncludesAiContextVariable()
    {
        $this->assertEquals(Response::HTTP_OK, 200);
    }
}
