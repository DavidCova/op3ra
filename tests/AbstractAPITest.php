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

    protected static $testAdminUser = [
        'username' => 'test_admin',
        'password' => 'test_admin_password',
        'roles' => [ 'ROLE_ADMIN' ]
    ];

    protected static $testUserToken;
    protected static $testAdminToken;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->prepare_user(static::$testUser);
        $this->prepare_user(static::$testAdminUser);

        static::$testUserToken  = $this->get_token(static::$testUser);
        static::$testAdminToken = $this->get_token(static::$testAdminUser);
    }

    /**
     * Boot Symfony kernel and prepare the user using the 'app:user-create' command for testing.
     *
     * @param array $user The user data to be passed as input to the command.
     */
    protected function prepare_user($user)
    {
        // Boot Symfony kernel
        $kernel = self::bootKernel();

        // Create Symfony console application
        $application = new Application($kernel);

        // Disable auto-exit to allow further interaction
        $application->setAutoExit(FALSE);

        // Find the 'app:user-create' command
        $command = $application->find('app:user-create');

        // Create buffered output to capture command output
        $output = new BufferedOutput();

        // Create array input with user data
        $input = new ArrayInput($user);

        // Run the 'app:user-create' command with provided input
        $command->run($input, $output);
    }

    /**
     * @return string Token
     */
    protected function get_token($user): string
    {
        $response = $this->post('/login', $user);
        return json_decode($response->getContent(), TRUE)['token'];
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
