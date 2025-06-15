<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\PriceHistory;
class PriceScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ],
        ]);
    }

    /**
     * メルカリで指定キーワードの商品価格を取得
     *
     * @param string $keyword
     * @return array<int> 価格一覧
     */
    public function fetchMercariPrices(string $keyword): array
    {
        $url = 'https://www.mercari.com/jp/search/?keyword=' . urlencode($keyword);

        try {
            $response = $this->client->request('GET', $url);
        } catch (\Exception $e) {
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $crawler = new Crawler((string)$response->getBody());
        $prices = [];

        $crawler
            ->filter('.priceContainer__a6f874a2 .merPrice .number__6b270ca7')
            ->each(function (Crawler $node) use (&$prices) {
                $price = (int) preg_replace('/[^0-9]/', '', $node->text());
                if ($price > 0) {
                    $prices[] = $price;
                }
            });

        return $prices;
    }

    /**
     * ヤフオクで指定キーワードの終了済みオークション価格と終了日時を取得
     *
     * @param string $keyword
     * @return array<int, array{date: string, price: int}>
     */
    public function fetchYahooPrices(string $keyword): array
    {
        $url = 'https://auctions.yahoo.co.jp/closedsearch/closedsearch'
            . '?p='     . urlencode($keyword)
            . '&exflg=1'
            . '&n=50';

        try {
            $response = $this->client->request('GET', $url);
        } catch (\Exception $e) {
            \Log::error('Yahoo Closed request failed: ' . $e->getMessage());
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            \Log::error('Yahoo Closed returned HTTP ' . $response->getStatusCode());
            return [];
        }

        \Log::debug('Yahoo Closed HTML snippet:', [
            'html' => mb_substr((string)$response->getBody(), 0, 500),
        ]);

        $crawler = new Crawler((string)$response->getBody());
        $prices  = [];

        $crawler->filter('div.Product__infoCell.Product__infoCell--left')->each(function (Crawler $node) use (&$prices) {
          
            if (!$node->filter('span.Product__time')->count()) {
                return;
            }
            $dateText = trim($node->filter('span.Product__time')->text());
            $dt = \DateTime::createFromFormat('m/d H:i', $dateText);
            if (!$dt) {
                return;
            }
            $dt->setDate((int)date('Y'), (int)$dt->format('m'), (int)$dt->format('d'));
            $formattedDate = $dt->format('Y-m-d');

            if (!$node->filter('span.FilterItem__priceValue')->count()) {
                return;
            }
            $priceText = $node->filter('span.FilterItem__priceValue')->eq(0)->text();
            $price = (int) preg_replace('/[^0-9]/', '', $priceText);

            if ($price > 0) {
                $prices[] = [
                    'date'  => $formattedDate,
                    'price' => $price,
                ];
            }
        });

        \Log::debug('Parsed yahooPrices:', ['count' => count($prices), 'data' => $prices]);

        return $prices;
    }
     public function saveYahooPrices(int $postId, array $prices): void
    {
        foreach ($prices as $item) {
            PriceHistory::updateOrCreate(
                [
                    'post_id'     => $postId,
                    'recorded_at' => $item['date'],
                ],
                ['price' => $item['price']]
            );
        }
    }
    public function scrapeAndSave(int $postId, string $keyword): void
{
    // ヤフオク価格取得
    $yahooPrices = $this->fetchYahooPrices($keyword);
    $this->saveYahooPrices($postId, $yahooPrices);

}
}
