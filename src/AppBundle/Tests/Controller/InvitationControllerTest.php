<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvitationControllerTest extends WebTestCase
{
    public function testReceiveinvitation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'view');
    }

    public function testGetallinvitation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'getall');
    }

    public function testEditinvitationstatus()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'edit');
    }

    public function testCreateinvitation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'create');
    }

}
