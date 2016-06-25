<?php namespace Arcanedev\Currencies\Tests\Http;

use Arcanedev\Currencies\Http\Client;
use Arcanedev\Currencies\Tests\TestCase;

/**
 * Class     ClientTest
 *
 * @package  Arcanedev\Currencies\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ClientTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\Currencies\Http\Client */
    private $client;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = new Client;
    }

    public function tearDown()
    {
        unset($this->client);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\Currencies\Contracts\Http\Client::class,
            \Arcanedev\Currencies\Http\Client::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->client);
        }
    }

    /** @test */
    public function it_can_set_and_get_base_url()
    {
        $this->client->setBaseUrl($url = 'http://localhost');

        $this->assertSame($url, $this->client->getBaseUrl());
    }

    /** @test */
    public function it_can_make_get_request()
    {
        $this->client->setBaseUrl('http://jsonplaceholder.typicode.com');

        $response = $this->client->get('/posts');
        $posts    = json_decode($response, true);

        $this->assertJson($response);
        $this->assertCount(100, $posts);

        $response = $this->client->get('/posts', ['userId' => 1]);
        $posts    = json_decode($response, true);

        $this->assertJson($response);
        $this->assertCount(10, $posts);
    }
}
