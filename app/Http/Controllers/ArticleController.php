<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query    = Article::published()->latest();
        $category = $request->query('category', 'semua');
        $search   = $request->query('q');

        $query->byCategory($category);

        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        $articles   = $query->paginate(9)->withQueryString();
        $categories = ['semua', 'perawatan', 'penyakit', 'tips', 'nutrisi'];

        return view('education.index', compact('articles', 'categories', 'category', 'search'));
    }

    public function show($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('education.show', compact('article', 'related'));
    }
}
