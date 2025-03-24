<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Oauth2ServerTest extends WebTestCase
{
  public function testOauth2Server(): void
  {
    $client = static::createClient();

    $clientId = '5a6506815ff5fc16daacb2b4379d53bc';
    $clientSecret = '739ca77d6d2cebc74d5f5360177eac6c50bb1433752a414cf761cf3dfe10fbe4c0c7f4f64a8bfc064de35433b0d77a2fe8fd57b2d0437d7bfb9d21ca78f4c4ce';
    $expectedRedirectUri = 'https://fabien-roy.netlify.app/callback';
    $csrfToken = 'a0d9f59b4d80e287cbe1ba36771ac2a011a173f2';
    $scope = 'default';

    $client->request('GET', '/authorize', [
      'response_type' => 'code',
      'client_id' => $clientId,
      'redirect_uri' => $expectedRedirectUri,
      'scope' => $scope,
      'state' => $csrfToken,
    ]);
    $this->assertResponseStatusCodeSame(302);

    $client->followRedirect();
    $this->assertResponseIsSuccessful();

    $client->submitForm('Connexion', [
      'email' => 'fabienroy2@gmail.com',
      'password' => '12345678',
    ]);
    $this->assertResponseStatusCodeSame(302);

    $client->followRedirect();
    $this->assertResponseStatusCodeSame(302);

    $redirectUri = $client->getResponse()->headers->get('location', '');
    list($uri, $queryString) = explode('?', $redirectUri);
    parse_str($queryString, $parameters);
    $this->assertEquals($expectedRedirectUri, $uri);
    $this->assertArrayHasKey('code', $parameters);
    $this->assertArrayHasKey('state', $parameters);
    $this->assertEquals($csrfToken, $parameters['state']);

    $client->request('POST', '/token', [
      'grant_type' => 'authorization_code',
      'client_id' => $clientId,
      'client_secret' => $clientSecret,
      'redirect_uri' => $expectedRedirectUri,
      'code' => $parameters['code'],
    ]);

    $this->assertResponseIsSuccessful();
    if (false === ($responseContent = $client->getResponse()->getContent())) {
      $this->fail('Invalid token request response.');
    }

    $data = json_decode($responseContent, true);
    $this->assertArrayHasKey('token_type', $data);
    $this->assertEquals('Bearer', $data['token_type']);
    $this->assertArrayHasKey('expires_in', $data);
    $this->assertArrayHasKey('access_token', $data);
    $this->assertArrayHasKey('refresh_token', $data);

    $client->request('POST', '/token', [
      'grant_type' => 'refresh_token',
      'refresh_token' => $data['refresh_token'],
      'client_id' => $clientId,
      'client_secret' => $clientSecret,
      'scope' => $scope,
    ]);

    $this->assertResponseIsSuccessful();
    if (false === ($responseContent = $client->getResponse()->getContent())) {
      $this->fail('Invalid refresh token request response.');
    }

    $data = json_decode($responseContent, true);
    $this->assertArrayHasKey('token_type', $data);
    $this->assertEquals('Bearer', $data['token_type']);
    $this->assertArrayHasKey('expires_in', $data);
    $this->assertArrayHasKey('access_token', $data);
    $this->assertArrayHasKey('refresh_token', $data);
  }
}
