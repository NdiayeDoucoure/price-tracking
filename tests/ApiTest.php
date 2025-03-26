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
    
    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NDI5NzkxMTEsImV4cCI6MTc0Mjk4MjcxMSwicm9sZXMiOlsiUk9MRV9DT01NRVJDQU5UIiwiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiY29tbWVyY2FudEB1bml2LnNuIn0.X3CjOoHlzq13qKGvv8r91-MAAd3c2slClKU0CQOn8BSzZv3N3Teisa6h3wcOmjk3esj8mCSu3hyEgrK336M8lw-rrBVlb0xadzZb5dIqARrLKzlFlWMwuGb0NsZ11eAKaKhS-GFZ_wPMgAao-iikZ1l1wXEaEhDaWeyZW6ZzJHv4hb5ZxyDsX2oN67NE1_FLyz8LGhu31m_FA8lyEqKxUCgQg2NzaESSZ2rk4goKkRctm0oB3rViSYoQAgk4ZWzMMYGHphxSplkMiy_a6p2kZhlM22YTj2UHiG1fuCGPka8H1ooPdvHCLnztShSNo8tX88o-7TADUVDK9Xcq99V9sw';
    $client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);
    
    $client->request('GET', '/api/produits');
    $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200 OK
    
    $content = json_decode($client->getResponse()->getContent(), true);
    
    $this->assertIsArray($content);
    $this->assertGreaterThan(0, count($content), 'Il y a des produits retournés');
}

}
