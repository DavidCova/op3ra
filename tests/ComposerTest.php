<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\Depends;

class ComposerTest extends AbstractAPITest
{
    private static $testComposer = [
        'firstName' => 'Wolfgang',
        'lastName' => 'Mozart',
        'dateOfBirth' => '1756-01-27',
        'countryCode' => 'AT'
    ];

    public function testCreateReturns422(): void
    {
        $invalidComposer = static::$testComposer;

        unset($invalidComposer['firstName']);
        
        $response = $this->post('/composer', $invalidComposer, static::$testAdminToken);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreateWithInvalidNameReturns422(): void
    {
        $invalidComposer = static::$testComposer;

        unset($invalidComposer['countryCode']);
        
        $response = $this->post('/composer', $invalidComposer, static::$testAdminToken);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreateWithInvalidCountryCodeReturns422(): void
    {
        $invalidComposer = static::$testComposer;

        $invalidComposer['countryCode'] = 'Atlantis';
        
        $response = $this->post('/composer', $invalidComposer, static::$testAdminToken);

        $this->assertSame(422, $response->getStatusCode());
    }

    /**
     * Test that 401 is returned when no token is provided
     */
    public function testCreateIsUnauthorizedWithoutToken(): void
    {
        $response = $this->post('/composer', static::$testComposer, NULL);

        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Test that 403 is returned when used does not have sufficient permissions to access resource
     */
    public function testCreateWithInsuficientUserPermissions(): void
    {
        $response = $this->post('/composer', static::$testComposer, static::$testUserToken);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $response = $this->post('/composer', static::$testComposer, static::$testAdminToken);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertNotEmpty($json['id']);

        static::$testComposer['id'] = $json['id'];
    }

    #[Depends('testCreate')]
    public function testIndex(): void
    {
        $response = $this->get('/composer', static::$testUserToken);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertTrue(in_array(static::$testComposer, $json));
    }

    #[Depends('testCreate')]
    public function testShow(): void
    {
        $response = $this->get('/composer/'. static::$testComposer['id'], static::$testUserToken);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertEquals(static::$testComposer, $json);
    }

    #[Depends('testCreate')]
    public function testUpdate(): void
    {
        static::$testComposer['firstName'] = 'Woflgang Amadeus';

        $response = $this->put('/composer/'. static::$testComposer['id'], static::$testComposer, static::$testAdminToken);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), TRUE);

        $this->assertEquals(static::$testComposer, $json);
    }

    #[Depends('testCreate')]
    public function testDelete(): void
    {
        $response = $this->delete('/composer/'. static::$testComposer['id'], static::$testAdminToken);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response = $this->client->getResponse()->getContent());
    }

}
