<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\Depends;

class SymfonyTest extends AbstractApiTest
{
    private static $testSymfony = [
        'name' => 'No 1',
        'description' => null,
        'finishedAt' => null,
    ];

    /**
     * @depends testCreate
     */
    public function testIndex(): void
    {
        $response = $this->get('/symfony');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertTrue(in_array(static::$testSymfony, $json));
    }

    public function testCreateWithInvalidNameReturns422(): void
    {
        $invalidSymphony = static::$testSymfony;

        unset($invalidSymphony['name']);
        
        $response = $this->post('/symfony', $invalidSymphony);

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
        $response = $this->post('/composer', $composer);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $composerJson = json_decode($response->getContent(), true);

        static::$testSymfony['composerId'] = $composerJson['id'];
        $response = $this->post('/symfony', static::$testSymfony);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $json = json_decode($response->getContent(), true);
        $this->assertNotEmpty($json['id']);
        $this->assertNotEmpty($json['createdAt']);
        static::$testSymfony['id'] = $json['id'];
        static::$testSymfony['createdAt'] = $json['createdAt'];
    }

    #[Depends('testCreate')]
    public function testShow(): void
    {
        $response = $this->get('/symfony/' . static::$testSymfony['id']);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(static::$testSymfony, $json);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(): void
    {
        static::$testSymfony['description'] = 'Foo bar long text description';
        $response = $this->put('/symfony/' . static::$testSymfony['id'], static::$testSymfony);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(static::$testSymfony, $json);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $response = $this->delete('/symfony/' . static::$testSymfony['id']);
        $this->assertSame(204, $response->getStatusCode());
    }
}
