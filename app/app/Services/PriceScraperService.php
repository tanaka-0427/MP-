<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\Storage;
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
   public function fetchYahooPrices(string $keyword, int $maxPages = 5): array
{
    $allPrices = [];
    $perPage = 50;

    for ($page = 1; $page <= $maxPages; $page++) {
        $start = ($page - 1) * $perPage + 1;

        $url = 'https://auctions.yahoo.co.jp/closedsearch/closedsearch'
            . '?p=' . urlencode($keyword)
            . '&va=' . urlencode($keyword)
            . '&exflg=1'
            . '&n=' . $perPage
            . '&b=' . $start;

        try {
            $response = $this->client->request('GET', $url);
        } catch (\Exception $e) {
            \Log::error("Yahoo Closed request failed (page $page): " . $e->getMessage());
            break;
        }

        if ($response->getStatusCode() !== 200) {
            \Log::error("Yahoo Closed returned HTTP {$response->getStatusCode()} (page $page)");
            break;
        }

        $html = (string)$response->getBody();
        $crawler = new Crawler($html);

        $prices = [];
        $crawler->filter('li.Product')->each(function (Crawler $node) use (&$prices) {
            if (!$node->filter('.Product__priceValue')->count() || !$node->filter('span.Product__time')->count()) {
                return;
            }

            $priceText = $node->filter('.Product__priceValue')->text();
            $price = (int) preg_replace('/[^0-9]/', '', $priceText);

            $dateText = trim($node->filter('span.Product__time')->text());
            $dt = \DateTime::createFromFormat('m/d H:i', $dateText);
            if (!$dt) {
                return;
            }
            $dt->setDate((int)date('Y'), (int)$dt->format('m'), (int)$dt->format('d'));
            $formattedDate = $dt->format('Y-m-d');

            if ($price > 0) {
                $prices[] = [
                    'date'  => $formattedDate,
                    'price' => $price,
                ];
            }
        });

        if (count($prices) === 0) {
           
            break;
        }

        $allPrices = array_merge($allPrices, $prices);
    }

    \Log::debug('Parsed total yahooPrices:', ['count' => count($allPrices)]);

    return $allPrices;
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
    $filename = 'scraping/output_' . $keyword . '.json';
    Storage::disk('public')->put($filename, json_encode([
        'keyword' => $keyword,
        'items' => $yahooPrices,
        'average_price' => $this->calculateAverage($yahooPrices),
        'median_price'  => $this->calculateMedian($yahooPrices),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    private function calculateAverage(array $items): int
    {
        if (empty($items)) return 0;
        $sum = array_sum(array_column($items, 'price'));
        return (int) round($sum / count($items));
    }

    private function calculateMedian(array $items): int
    {
        if (empty($items)) return 0;
        $prices = array_column($items, 'price');
        sort($prices);
        $count = count($prices);
        $middle = (int) floor($count / 2);

        return $count % 2
        ? $prices[$middle]
        : (int) round(($prices[$middle - 1] + $prices[$middle]) / 2);
    }
}
