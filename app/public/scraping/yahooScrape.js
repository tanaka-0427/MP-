const puppeteer = require('puppeteer');
const fs = require('fs');

const keyword = process.argv[2] || 'ニンテンドースイッチ';
const safeKeyword = keyword.replace(/[^\w\u3000-\u30FF\u4E00-\u9FFF]/g, '_'); 

(async () => {
  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();

  const url = `https://auctions.yahoo.co.jp/closedsearch/closedsearch?p=${encodeURIComponent(keyword)}&va=${encodeURIComponent(keyword)}&b=1&n=50`;
  await page.goto(url, { waitUntil: 'networkidle2' });

  const items = await page.evaluate(() => {
    const results = [];
    const listItems = document.querySelectorAll('li.Product');

    listItems.forEach(item => {
      const priceEl = item.querySelector('.Product__priceValue');
      const dateEl = item.querySelector('dd.Product__data > span.Product__time');

      if (priceEl && dateEl) {
        const priceText = priceEl.innerText.replace(/[^\d]/g, '');
        const price = parseInt(priceText, 10);
        const date = dateEl.innerText.trim();

        if (!isNaN(price)) {
          results.push({ price, date });
        }
      }
    });

    return results;
  });

  // データがない場合はエラー
  if (items.length === 0) {
    const html = await page.content();
    fs.writeFileSync(`debug_${safeKeyword}.html`, html); 
    console.error('❌ スクレイピングに失敗しました。データが0件です。');
    await browser.close();
    process.exit(1);
  }

  // 平均・中央値の計算
  const prices = items.map(item => item.price).sort((a, b) => a - b);
  const avg = prices.reduce((a, b) => a + b, 0) / prices.length || 0;
  const median = prices.length % 2 === 0
    ? (prices[prices.length / 2 - 1] + prices[prices.length / 2]) / 2
    : prices[Math.floor(prices.length / 2)];

  const result = {
    keyword,
    average_price: Math.round(avg),
    median_price: median,
    items
  };

  // 保存
  fs.writeFileSync(`output_${safeKeyword}.json`, JSON.stringify(result, null, 2));

  console.log(JSON.stringify(result, null, 2));

  await browser.close();
})();
