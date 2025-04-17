<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's username.
     */
    public function updateUsername(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $id,
                'regex:/^[a-zA-Z0-9]+$/'
            ],
        ], [
            'username.required' => 'A felhasználónév megadása kötelező.',
            'username.unique' => 'Ez a felhasználónév már foglalt.',
            'username.max' => 'Túl hosszú a felhasználónév.',
            'username.regex' => 'A felhasználónév csak betűket és számokat tartalmazhat.',
        ]);

        try {
            $user->username = $request->username;
            $user->save();

            return redirect()->route('profile.edit')->with('status', 'username-updated');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('status', 'username-not-updated');
        }
    }

    /**
     * Update the user's ingame name.
     */
    public function updateCharacterName(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if (!(Auth::user()->isAdmin || Auth::user()->is_superadmin)) {
            return redirect()->route('profile.edit')->with('status', 'not-authorized');
        }

        $validatedData = $request->validate([
            'charactername' => [
                'required',
                'string',
                'max:255',
                'unique:users,charactername,' . $id,
                'regex:/^[a-zA-Z]+$/'
            ],
        ], [
            'charactername.required' => 'Az InGame név megadása kötelező.',
            'charactername.unique' => 'Ez az InGame név már foglalt.',
            'charactername.max' => 'Túl hosszú az InGame név.',
            'charactername.regex' => 'Az InGame név csak betűket tartalmazhat.',
        ]);

        try {
            $user->charactername = $request->charactername;
            $user->save();

            return redirect()->route('profile.edit')->with('status', 'charactername-updated');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('status', 'charactername-not-updated');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'username' => [
                'nullable',
                'string',
                'max:255',
                'unique:users,username,' . $id,
                'regex:/^[a-zA-Z0-9]+$/'
            ],
            'charactername' => [
                'nullable',
                'string',
                'max:255',
                'unique:users,charactername,' . $id,
                'regex:/^[a-zA-Z]+$/'
            ],
        ], [
            'username.unique' => 'Ez a felhasználónév már foglalt.',
            'username.max' => 'Túl hosszú a felhasználónév.',
            'username.regex' => 'A felhasználónév csak betűket és számokat tartalmazhat.',
            'charactername.unique' => 'Ez az InGame név már foglalt.',
            'charactername.max' => 'Túl hosszú az InGame név.',
            'charactername.regex' => 'Az InGame név csak betűket tartalmazhat.',
        ]);

        try {
            if ($request->filled('username')) {
                $user->username = $request->username;
            }
            if ($request->filled('charactername') && (Auth::user()->isAdmin || Auth::user()->is_superadmin)) {
                $user->charactername = $request->charactername;
            }

            $user->save();

            return redirect()->route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('status', 'profile-not-updated');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePicture(Request $request)
    {
        // Validáció
        $validatedData = $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5 MB
        ]);
    
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/' . $filename);
    
            // Egyszerű fájl mentés
            $image->move(storage_path('app/public/'), $filename);
    
            // Adatbázis frissítése
            $user = Auth::user();
            $user->profile_picture = $filename;
            $user->save();
        }
    
        return redirect()->back()->with('success', 'Profilkép sikeresen frissítve!');
    }

    public function removePicture()
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            // Profilkép törlése a szerverről
            $path = storage_path('app/public/' . $user->profile_picture);
            if (file_exists($path)) {
                unlink($path);
            }

            // Profilkép mező nullázása
            $user->profile_picture = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profilkép sikeresen törölve!');
    }

    /**
     * Update the user's game data.
     */
    public function updateGameData(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'field' => 'required|string|in:played_minutes,badges,created_at',
            'value' => 'required|string',
        ]);

        try {
            switch ($validatedData['field']) {
                case 'played_minutes':
                    $value = (int) preg_replace('/[^0-9]/', '', $validatedData['value']);
                    $user->played_minutes = $value;
                    break;
                case 'badges':
                    $value = (int) preg_replace('/[^0-9]/', '', $validatedData['value']);
                    $user->badges = $value;
                    break;
                case 'created_at':
                    $value = date('Y-m-d H:i:s', strtotime($validatedData['value']));
                    $user->created_at = $value;
                    break;
            }

            $user->save();
            return response()->json(['success' => true, 'message' => 'Adat sikeresen frissítve!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hiba történt a mentés során!'], 500);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'A jelenlegi jelszó megadása kötelező.',
            'current_password.current_password' => 'A jelenlegi jelszó helytelen.',
            'new_password.required' => 'Az új jelszó megadása kötelező.',
            'new_password.min' => 'Az új jelszónak legalább 8 karakterből kell állnia.',
            'new_password.confirmed' => 'A két jelszó nem egyezik.',
        ]);

        try {
            $user->update([
                'password' => bcrypt($request->new_password)
            ]);

            return redirect()->route('profile.edit')->with('status', 'password-updated');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('status', 'password-not-updated');
        }
    }
};