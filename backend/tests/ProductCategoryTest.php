<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ProductCategory;

class ProductCategoryTest extends ApiTestCase
{
    public function testCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/product_categories');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ProductCategory',
            '@id' => '/api/product_categories',
            '@type' => 'hydra:Collection'
        ]);

        $this->assertCount(10, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(ProductCategory::class);
    }

    public function testCreateProductCategory(): void
    {
        $response = static::createClient()->request('POST', '/api/product_categories', ['json' => [
            'title' => 'Test Category'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/ProductCategory',
            '@type' => 'ProductCategory',
            'title' => 'Test Category',
        ]);

        $this->assertMatchesRegularExpression('~^/api/product_categories/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(ProductCategory::class);
    }

    public function testCreateInvalidProductCategory(): void
    {
        $response = static::createClient()->request('POST', '/api/product_categories', ['json' => [
            'title' => 'Te'
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'title: This value is too short. It should have 3 characters or more.',
        ]);
    }

    public function testUpdateProductCategory(): void
    {
        $category = $this->findIriBy(ProductCategory::class, ['title' => 'Category 1']);
        
        $response = static::createClient()->request('PUT', $category, ['json' => [
            'title' => 'Updated title'
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/ProductCategory',
            '@type' => 'ProductCategory',
            'title' => 'Updated title',
        ]);
    }

    public function testDeleteProductCategory(): void
    {
        $title = 'Category 1';
        
        $client = static::createClient();
        $iri = $this->findIriBy(ProductCategory::class, ['title' => $title]);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            $this->getContainer()->get('doctrine')->getRepository(ProductCategory::class)
                ->findOneBy(['title' => $title])
        );
    }
}
