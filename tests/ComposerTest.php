<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\Depends;

class ComposerTest extends AbstractAPITest
{
    private static $test_composer = [
        'firstName' => 'Wolfgang',
        'lastName' => 'Mozart',
        'dateOfBirth' => '1756-01-27',
        'countryCode' => 'AT'
    ];

    public function testCreateReturns422(): void
    {
        $invalidComposer = static::$test_composer;

        unset($invalidComposer['firstName']);
        
        $response = $this->post('/composer', $invalidComposer);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreateWithInvalidNameReturns422(): void
    {
        $invalidComposer = static::$test_composer;

        unset($invalidComposer['countryCode']);
        
        $response = $this->post('/composer', $invalidComposer);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreateWithInvalidCountryCodeReturns422(): void
    {
        $invalidComposer = static::$test_composer;

        $invalidComposer['countryCode'] = 'Atlantis';
        
        $response = $this->post('/composer', $invalidComposer);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $response = $this->post('/composer', static::$test_composer);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertNotEmpty($json['id']);

        static::$test_composer['id'] = $json['id'];
    }

    #[Depends('testCreate')]
    public function testIndex(): void
    {
        $response = $this->get('/composer');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertTrue(in_array(static::$test_composer, $json));
    }

    #[Depends('testCreate')]
    public function testShow(): void
    {
        $response = $this->get('/composer/'. static::$test_composer['id']);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertEquals(static::$test_composer, $json);
    }

    #[Depends('testCreate')]
    public function testUpdate(): void
    {
        static::$test_composer['firstName'] = 'Woflgang Amadeus';

        $response = $this->put('/composer/'. static::$test_composer['id'], static::$test_composer);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertEquals(static::$test_composer, $json);
    }

    #[Depends('testCreate')]
    public function testDelete(): void
    {
        $response = $this->delete('/composer/'. static::$test_composer['id']);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response = $this->client->getResponse()->getContent());
    }

}
