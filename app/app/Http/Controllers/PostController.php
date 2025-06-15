<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Services\PriceScraperService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('main.index', compact('posts'));
    }

    // 投稿フォーム表示
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', [
            'categories' => $categories,
            'post' => new Post(),
        ]);
    }

    // 編集フォーム表示
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.create', [
            'categories' => $categories,
            'post' => $post,
        ]);
    }

    // 投稿確認・戻る・保存処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'price_original' => 'required|integer',
            'price_purchased' => 'required|integer',
            'price_current' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // 確認画面へ
        if ($request->input('action') === 'confirm') {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('temp', 'public');
                session(['temp_image_path' => $imagePath]);
            }

            $categories = Category::all();
            $categoryName = $categories->where('id', $validated['category_id'])->first()->name ?? '';

            return view('posts.confirm', [
                'data' => $validated,
                'categoryName' => $categoryName,
                'imagePath' => $imagePath,
            ]);
        }

        // 戻る処理
        if ($request->input('action') === 'back') {
            $categories = Category::all();
            return view('posts.create', [
                'post' => (object) $request->except('_token', 'action'),
                'categories' => $categories,
            ]);
        }

        // 保存処理
        if ($request->input('action') === 'submit') {
            $post = new Post();
            $post->user_id = auth()->id();
            $post->title = $validated['title'];
            $post->category_id = $validated['category_id'];
            $post->content = $validated['content'];
            $post->price_original = $validated['price_original'];
            $post->price_purchased = $validated['price_purchased'];
            $post->price_current = $validated['price_current'] ?? null;

            $tempImagePath = session('temp_image_path');
            if ($tempImagePath) {
                $finalPath = str_replace('temp/', 'images/', $tempImagePath);
                Storage::disk('public')->move($tempImagePath, $finalPath);
                $post->image = $finalPath;
                session()->forget('temp_image_path');
            }

            $post->save();
            $scraper = app(PriceScraperService::class);
            $scraper->scrapeAndSave($post->id, $post->title);
            return redirect()->route('posts.show', $post->id)
                ->with('success', '投稿が完了しました');
        }

        // 不正な操作
        abort(400, '不正な操作です');
    }

    // 投稿検索
    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->with('user', 'category')
            ->latest()
            ->paginate(10);

        return view('posts.search', compact('posts', 'query'));
    }

    // 投稿詳細表示
   public function show($id, PriceScraperService $scraper)
{
    $post = Post::with(['likes', 'comments.user', 'user', 'category'])->findOrFail($id);

    $keyword = $post->title;
    $safeKeyword = urlencode($keyword);

    // ヤフオクのファイル読み込み
    $yahooFilePath = "scraping/output_{$keyword}.json";
    $yahooPrices = [];
    $yahooPricesByDate = [];

    if (Storage::disk('public')->exists($yahooFilePath)) {
        $json = Storage::disk('public')->get($yahooFilePath);
        $data = json_decode($json, true);

        \Log::debug('Yahoo file exists and loaded:', ['file' => $yahooFilePath, 'data' => $data]);

        if (isset($data['items'])) {
            $yahooPrices = $data['items'];

            // 日付ごとの平均価格
            $grouped = collect($yahooPrices)->groupBy('date');
            $yahooPricesByDate = $grouped->map(function ($items, $date) {
                $avg = (int) round(collect($items)->avg('price'));
                return ['date' => $date, 'avg_price' => $avg];
            })->values();
        } else {
            \Log::debug('Key "items" not found in Yahoo JSON.', ['data_keys' => array_keys($data ?? [])]);
        }
    } else {
        \Log::debug('Yahoo file does not exist.', ['file' => $yahooFilePath]);
    }

    // メルカリのファイル読み込み
    $mercariFilePath = "scraping/mercari_output_{$keyword}.json";
    $mercariPrices = [];
    $mercariPricesByDate = [];

    if (Storage::disk('public')->exists($mercariFilePath)) {
        $json = Storage::disk('public')->get($mercariFilePath);
        $data = json_decode($json, true);

        \Log::debug('Mercari file exists and loaded:', ['file' => $mercariFilePath, 'data' => $data]);

        if (isset($data['items'])) {
            $mercariPrices = $data['items'];

            // 日付ごとの平均価格
            $grouped = collect($mercariPrices)->groupBy('date');
            $mercariPricesByDate = $grouped->map(function ($items, $date) {
                $avg = (int) round(collect($items)->avg('price'));
                return ['date' => $date, 'avg_price' => $avg];
            })->values();
        } else {
            \Log::debug('Key "items" not found in Mercari JSON.', ['data_keys' => array_keys($data ?? [])]);
        }
    } else {
        \Log::debug('Mercari file does not exist.', ['file' => $mercariFilePath]);
    }

    return view('posts.show', compact(
        'post',
        'yahooPrices', 'yahooPricesByDate',
        'mercariPrices', 'mercariPricesByDate'
    ));
}
    // 投稿更新処理
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'price_original' => 'required|integer',
            'price_purchased' => 'required|integer',
            'price_current' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = Post::findOrFail($id);

        // 自分の投稿か確認
        if ($post->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $post->title = $validated['title'];
        $post->category_id = $validated['category_id'];
        $post->content = $validated['content'];
        $post->price_original = $validated['price_original'];
        $post->price_purchased = $validated['price_purchased'];
        $post->price_current = $validated['price_current'] ?? null;

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return redirect()->route('posts.show', $post->id)
            ->with('success', '投稿を更新しました。');
    }

    // 投稿削除処理
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // 自分の投稿か確認
        if ($post->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        // 画像削除
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // 投稿を削除
        $post->delete();

        return redirect()->route('main')->with('success', '投稿が削除されました。');
    }
}
