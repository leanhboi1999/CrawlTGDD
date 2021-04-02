<?php
namespace App\Scraper;

use App\Models\Product;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{

    public function crawl($number)
    {
        $url = 'https://www.thegioididong.com/aj/CategoryV6/Product?Category=42&PageSize='.$number;
        $cient = new Client();
        $crawler = $cient->request('GET', $url);
        $id = 0;
        $crawler->filter('ul.homeproduct li.item')->each(
            function (Crawler $node) {
                $name = $node->filter('h3')->text();
                $price = $node->filter('.price strong')->text();
                $wholeStar = $node->filter('.icontgdd-ystar')->count();
                $halfStar = $node->filter('.icontgdd-hstar')->count();
                $rate = $wholeStar + 0.5 * $halfStar;

                $price = preg_replace('/\D/', '', $price);
                $product = new Product;
                $product->name = $name;
                $product->price = $price;
                $product->rate = $rate;
                $product->save();
            });
    }
}
