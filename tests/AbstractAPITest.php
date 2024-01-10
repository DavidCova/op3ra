<?php

namespace App\Tests;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class AbstractAPITest extends WebTestCase
{
    protected KernelBrowser $client;

    protected static $testUser = [
        'username' => 'test_user',
        'password' => 'test_password',
        'roles' => [ 'ROLE_USER' ]
    ];

    protected static $testUserToken;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $kernel      = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(FALSE);
        $command = $application->find('app:user-create');

        $output = new BufferedOutput();
        $input  = new ArrayInput(static::$testUser);
        $command->run($input, $output);

        $response = $this->post('/login', static::$testUser);

        static::$testUserToken = json_decode($response->getContent(), TRUE)['token'];
    }

    protected function get(string $uri, string $token = NULL): Response
    {
        $this->client->request('GET', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token ?? NULL,
        ]);

        return $this->client->getResponse();
    }

    protected function post(string $uri, array $data, string $token = NULL): Response
    {
        $this->client->request('POST', $uri, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token ?? NULL,
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function put(string $uri, array $data, string $token = NULL): Response
    {
        $this->client->request('PUT', $uri, [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token ?? NULL,
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function delete(string $uri, string $token = NULL): Response
    {
        $this->client->request('DELETE', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token ?? NULL,
        ]);

        return $this->client->getResponse();
    }

}
