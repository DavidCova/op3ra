<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\Depends;

class SymphonyTest extends AbstractApiTest
{
    private static $testSymphony = [
        'name' => 'No 1',
        'description' => null,
        'finishedAt' => null,
    ];

    /**
     * @depends testCreate
     */
    public function testIndex(): void
    {
        $response = $this->get('/symphony', static::$testUserToken);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertTrue(in_array(static::$testSymphony, $json));
    }

    public function testCreateWithInvalidNameReturns422(): void
    {
        $invalidSymphony = static::$testSymphony;

        unset($invalidSymphony['name']);
        
        $response = $this->post('/symphony', $invalidSymphony, static::$testAdminToken);

        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $composer = [
            'firstName' => 'Foo',
            'lastName' => 'Bar',
            'dateOfBirth' => date('Y-m-d'),
            'countryCode' => 'DE',
        ];
        $response = $this->post('/composer', $composer, static::$testAdminToken);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $composerJson = json_decode($response->getContent(), true);

        static::$testSymphony['composerId'] = $composerJson['id'];
        $response = $this->post('/symphony', static::$testSymphony, static::$testAdminToken);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $json = json_decode($response->getContent(), true);
        $this->assertNotEmpty($json['id']);
        $this->assertNotEmpty($json['createdAt']);
        static::$testSymphony['id'] = $json['id'];
        static::$testSymphony['createdAt'] = $json['createdAt'];
    }

    #[Depends('testCreate')]
    public function testShow(): void
    {
        $response = $this->get('/symphony/' . static::$testSymphony['id'], static::$testUserToken);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(static::$testSymphony, $json);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(): void
    {
        static::$testSymphony['description'] = 'Foo bar long text description';
        $response = $this->put('/symphony/' . static::$testSymphony['id'], static::$testSymphony, static::$testAdminToken);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(static::$testSymphony, $json);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $response = $this->delete('/symphony/' . static::$testSymphony['id'], static::$testAdminToken);
        $this->assertSame(204, $response->getStatusCode());
    }
}
