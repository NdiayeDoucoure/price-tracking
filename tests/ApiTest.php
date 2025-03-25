<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiTest extends WebTestCase
{
    /**
     * TestForGetProduitsWithoutAuthentication
     */
    public function testGetProduitsWithoutAuthentication(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/api/produits');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED); // 401 NotAuthorized
    }

    /**
     * TestForGetProduitsWithAuthentication
     */
    public function testGetProduitsWithAuthentication(): void
{
    $client = static::createClient();
    
    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NDI4NzEyMTIsImV4cCI6MTc0Mjg3NDgxMiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidXNlckB1bml2LnNuIn0.p_YfYkvZs91zNHWYt5RaBTPwKagDsTXNepx_xRWqY-VKUqVg5VA7ex5Vy5z1lqRNz9FCTCtB8q4nSL-Nc0JlBw4vf-xmgtJ6eIk0GlCgzElwEkX-FdRO4eZ8LpzX5mqlQIa2Ns42HOTWNFqnTcyeDLZkUgPmjLxXTHV_-AxC0NCG1enIIXNHu4QUtDSreTdai_5c-ZXz87FJ4sVdxZUFPGn2ix6SCU3Fr66wOah62gJH7MWV8XGzeyD7DaZpc6sh9JXjEmT8aZ7lUj9ufhS4NixBQijearAEBiQ7dmp08ViBoc9pNbZ8L5LIDHxDrGdFXgFpKemqTAi5X30bzVuaxQ'; // Ton token ici
    $client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);
    
    $client->request('GET', '/api/produits');
    $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200 OK
    
    $content = json_decode($client->getResponse()->getContent(), true);
    
    $this->assertIsArray($content);
    $this->assertGreaterThan(0, count($content), 'Il y a des produits retournés');
}

}
