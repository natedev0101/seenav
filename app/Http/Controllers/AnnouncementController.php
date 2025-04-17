<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use JBBCode\Parser;
use Emojione\Client as EmojiClient;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $announcement = new Announcement();
            $announcement->title = $validated['title'];
            $announcement->content = $validated['content'];
            $announcement->created_by = auth()->id();
            $announcement->save();

            return response()->json([
                'success' => true,
                'message' => 'Közlemény sikeresen létrehozva!',
                'announcement' => $announcement
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a közlemény létrehozása közben: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $announcements = Announcement::with('creator:id,charactername,profile_picture')
                ->orderBy('created_at', 'desc')
                ->paginate(3);

            if (request()->ajax()) {
                return view('view_dashboard', compact('announcements'))->fragment('announcementsList');
            }

            return view('view_dashboard', compact('announcements'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Hiba történt a közlemények betöltése közben.'], 500);
            }
            return back()->with('error', 'Hiba történt a közlemények betöltése közben.');
        }
    }

    public function show($id)
    {
        try {
            $announcement = Announcement::with('creator')->findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'created_at' => $announcement->created_at,
                    'user_id' => $announcement->created_by,
                    'user_name' => $announcement->creator->charactername
                ]);
            }

            return view('announcements.show', compact('announcement'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hiba történt a közlemény betöltése közben: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Hiba történt a közlemény betöltése közben.');
        }
    }

    public function edit($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            return response()->json([
                'success' => true,
                'announcement' => $announcement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a közlemény betöltése közben: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string'
            ]);

            $announcement->title = $validated['title'];
            $announcement->content = $validated['content'];
            $announcement->save();

            return response()->json([
                'success' => true,
                'message' => 'Közlemény sikeresen frissítve!',
                'announcement' => $announcement
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a közlemény módosítása közben: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Közlemény sikeresen törölve!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a közlemény törlése közben: ' . $e->getMessage()
            ], 500);
        }
    }
}
