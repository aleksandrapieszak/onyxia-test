<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\Page;

final class PageCollectionResolver implements QueryCollectionResolverInterface
{
    /**
     * @param iterable<Page> $collection
     *
     * @return iterable<Page>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        // Query arguments are in $context['args'].
//        foreach ($collection as $page) {
////            $book->title .= ' - LOMBARD 66';
////            $book->seoTitle = $book->title.'SEO';
//            // $books->test = 'test';
//            // $book->title = 'DUPA';
//            // $book->createdAt = 'Dziś';
//        }

        return $collection;
    }
}
