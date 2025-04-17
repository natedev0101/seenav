<?php

namespace App\Http\Controllers;

use App\Models\Subdivision;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubdivisionController extends Controller
{
    public function members(Subdivision $subdivision)
    {
        $members = $subdivision->users; // Az alosztályhoz tartozó felhasználók lekérése
        return view('subdivisions.members', compact('subdivision', 'members'));
    }
    /**
     * Alosztályok listázása.
     */
    public function assign()
{
    $subdivisions = Subdivision::all();
    $users = User::all();
    return view('subdivisions.assign', compact('subdivisions', 'users'));
}
     public function index()
    {
        $subdivisions = Subdivision::withCount('users')->get();
        return view('subdivisions.index', compact('subdivisions'));
    }

    /**
     * Alosztály létrehozásának nézete.
     */
    public function create()
    {
        // Összes létező alosztály ID lekérése
        $existingIds = DB::table('subdivisions')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        // Megkeressük a legkisebb hiányzó ID-t
        $nextId = 1;
        foreach ($existingIds as $id) {
            if ($id != $nextId) {
                break;
            }
            $nextId++;
        }

        return view('subdivisions.create', compact('nextId'));
    }

    /**
     * Alosztály mentése.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25|unique:subdivisions,name',
            'salary' => 'required|numeric|min:0',
            'color' => 'required|string',
            'next_id' => 'required|integer|min:1'
        ]);

        try {
            // Közvetlenül az adatbázisba szúrjuk be az ID-vel együtt
            $subdivision = DB::table('subdivisions')->insert([
                'id' => $request->next_id,
                'name' => $request->name,
                'salary' => $request->salary,
                'color' => $request->color,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Logolás
            Log::info('Alosztály létrehozás', ["Új alosztály létrehozva: {$request->name}, ID: {$request->next_id}"]);

            return redirect()->route('subdivisions.index')
                ->with('success', 'Az alosztály sikeresen létrehozva!');
        } catch (\Exception $e) {
            // Hiba esetén
            Log::error('Alosztály létrehozás hiba', ["Hiba: {$e->getMessage()}"]);
            
            return redirect()->route('subdivisions.index')
                ->with('error', 'Hiba történt az alosztály létrehozása közben.');
        }
    }

    /**
     * Alosztály szerkesztési nézete.
     */
    public function edit(Subdivision $subdivision)
    {
        return view('subdivisions.edit', compact('subdivision'));
    }

    /**
     * Alosztály frissítése.
     */
    public function update(Request $request, Subdivision $subdivision)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'color' => 'required|string'
        ]);

        $subdivision->update([
            'name' => $request->name,
            'salary' => $request->salary,
            'color' => $request->color
        ]);

        return redirect()->route('subdivisions.index')->with('success', 'Alosztály sikeresen frissítve!');
    }

    /**
     * Alosztály törlése.
     */
    public function destroy(Subdivision $subdivision)
    {
        // Jogosultság ellenőrzés (superadmin vagy admin)
        if (!auth()->user()->is_superadmin && !auth()->user()->is_admin) {
            return redirect()->route('subdivisions.index')->with('error', 'Nincs jogosultságod!');
        }

        try {
            // Alosztály ID mentése a törlés előtt
            $deletedId = $subdivision->id;
            $subdivisionName = $subdivision->name;

            // Alosztály törlése
            $subdivision->delete();
            
            // Logolás
            Log::info('Alosztály törlés', ["Törölt alosztály: {$subdivisionName}, ID: {$deletedId}, Törölte: " . auth()->user()->charactername]);
            
            return redirect()->route('subdivisions.index')
                ->with('success', 'Az alosztály sikeresen törölve!');
        } catch (\Exception $e) {
            // Hiba esetén
            Log::error('Alosztály törlés hiba', ["Alosztály: {$subdivision->name}, Hiba: {$e->getMessage()}"]);
            
            return redirect()->route('subdivisions.index')
                ->with('error', 'Hiba történt az alosztály törlése közben.');
        }
    }
    public function assignUpdate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subdivisions' => 'nullable|array',
            'subdivisions.*' => 'exists:subdivisions,id',
        ]);
    
        $user = User::findOrFail($request->user_id);
        $user->subdivisions()->sync($request->subdivisions ?? []);
    
        return redirect()->route('subdivisions.assign')->with('success', 'Alosztályok sikeresen frissítve!');
    }

    public function getUsersJson(Subdivision $subdivision)
    {
        // Csak admin és superadmin férhet hozzá
        if (!auth()->user()->is_superadmin && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = $subdivision->users()->select('charactername', 'is_online')->get();
        
        return response()->json([
            'users' => $users
        ]);
    }

    public function showUsers(Request $request, Subdivision $subdivision)
    {
        $search = $request->input('search');
        
        $users = $subdivision->users()
            ->when($search, function ($query) use ($search) {
                return $query->where('charactername', 'like', '%' . $search . '%');
            })
            ->orderBy('charactername')
            ->paginate(20);
            
        return view('subdivisions.members', compact('subdivision', 'users', 'search'));
    }

    /**
     * Kép feltöltése egy adott alosztályhoz.
     */
    public function uploadImage(Request $request, Subdivision $subdivision)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Előző kép törlése, ha van
        if ($subdivision->image) {
            Storage::disk('public')->delete($subdivision->image);
        }

        // Új kép feltöltése
        $path = $request->file('image')->store('subdivision_images', 'public');
        $subdivision->image = $path;
        $subdivision->save();

        return redirect()->back()->with('success', 'A kép sikeresen feltöltve.');
    }

    /**
     * Kép törlése egy adott alosztályból.
     */
    public function deleteImage(Subdivision $subdivision)
    {
        // Csak akkor törölje, ha létezik kép
        if ($subdivision->image) {
            Storage::disk('public')->delete($subdivision->image);
            $subdivision->image = null;
            $subdivision->save();
        }

        return redirect()->back()->with('success', 'A kép sikeresen törölve.');
    }
}