<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show', 'getLatest', 'markAsRead']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $archived = $request->boolean('archived', false);

        $query = News::with(['creator', 'archivedBy', 'views'])
            ->where('archived', $archived)
            ->orderBy('created_at', 'desc');

        $news = $query->get();

        // Csak az aktuális híreknél jelöljük meg olvasottként
        if (!$archived) {
            foreach($news as $item) {
                DB::table('news_views')->updateOrInsert(
                    [
                        'news_id' => $item->id,
                        'user_id' => $user->id
                    ],
                    [
                        'viewed_at' => now()
                    ]
                );
            }
        }

        if ($request->ajax()) {
            return view('news.partials.news-list', compact('news'));
        }

        return view('news.index', compact('news', 'archived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type' => 'required|in:info,warning,danger,success'
            ]);

            $news = new News($validated);
            $news->created_by = Auth::id();
            $news->save();

            Log::info('Új hír létrehozva', [
                'news_id' => $news->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('news.index')->with('success', 'Hír sikeresen létrehozva!');
        } catch (\Exception $e) {
            \Log::error('Hiba a hír létrehozása közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('news.index')->with('error', 'Hiba történt a hír létrehozása közben.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $news->load(['creator', 'archivedBy', 'views']);
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        $news->load(['creator', 'archivedBy']);
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type' => 'required|in:info,warning,danger,success'
            ]);

            $news->update($validated);

            Log::info('Hír frissítve', [
                'news_id' => $news->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hír sikeresen frissítve!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validációs hiba a hír frissítése közben', [
                'errors' => $e->errors(),
                'news_id' => $news->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Kérjük, ellenőrizze a megadott adatokat!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Hiba a hír frissítése közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a hír frissítése közben.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        try {
            $news->delete();

            Log::info('Hír törölve', [
                'news_id' => $news->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('success', 'Hír törölve');
        } catch (\Exception $e) {
            Log::error('Hiba a hír törlése közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Hiba történt a hír törlése közben.');
        }
    }

    /**
     * Get the latest active news.
     */
    public function getLatest()
    {
        try {
            $user = Auth::user();
            $news = News::with(['creator', 'archivedBy'])
                ->where('archived', false)
                ->orderBy('created_at', 'desc')
                ->get();

            $mappedNews = $news->map(function ($item) use ($user) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->content,
                    'type' => $item->type,
                    'created_at' => $item->created_at,
                    'time_ago' => $item->created_at->diffForHumans(),
                    'is_read' => $item->views()->where('user_id', $user->id)->exists(),
                    'creator' => [
                        'name' => $item->creator ? $item->creator->username : 'Ismeretlen'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'news' => $mappedNews
            ]);
        } catch (\Exception $e) {
            \Log::error('Hiba a hírek lekérése közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a hírek betöltése közben.'
            ], 500);
        }
    }

    /**
     * Hír megtekintésének rögzítése.
     */
    public function markAsRead(News $news)
    {
        try {
            $user = Auth::user();
            
            DB::table('news_views')->updateOrInsert(
                [
                    'news_id' => $news->id,
                    'user_id' => $user->id
                ],
                [
                    'viewed_at' => now()
                ]
            );

            return back()->with('success', 'Hír megjelölve olvasottként');
        } catch (\Exception $e) {
            \Log::error('Hiba a hír olvasottként jelölése közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Hiba történt a hír olvasottként jelölése közben.');
        }
    }

    /**
     * Archive the specified news item.
     */
    public function archive(News $news)
    {
        try {
            $news->update([
                'archived' => true,
                'archived_at' => now(),
                'archived_by' => auth()->id()
            ]);

            Log::info('Hír archiválva', [
                'news_id' => $news->id,
                'user_id' => Auth::id(),
                'archived_at' => now()
            ]);

            return redirect()->back()->with('success', 'A hír sikeresen archiválva lett.');
        } catch (\Exception $e) {
            Log::error('Hiba a hír archiválása közben', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Hiba történt az archiválás során.');
        }
    }
}
