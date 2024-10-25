<?php



namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTests
 *
 * @author Fish
 */
class FormationsControllerTests extends WebTestCase {
    
    
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
  
     public function testContenuPage(){
        $client = static::createClient();
    $crawler = $client->request('GET', '/formations');

    $this->assertCount(1, $crawler->filter('img[src*="banniere.jpg"]'), 'L\'image de la bannière n\'est pas présente.');

    $this->assertSelectorTextContains('th', 'formation');

    $this->assertCount(5, $crawler->filter('th')); 

    $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement'); 
    }
    
    
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('Eclipse n°8 : Déploiement');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations/formation/1', $uri);
    }   
    
    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse n°8 : Déploiement'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }  
    
    
}
