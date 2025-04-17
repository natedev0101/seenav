<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NameChangeRequest;
use App\Models\PreviousName;

class NameChangeController extends Controller
{
    public function showRequestForm()
    {
        if (auth()->user()->hasActiveNameChangeRequest()) {
            return redirect()->route('dashboard')
                ->with('error', 'Már van egy folyamatban lévő névváltási kérelmed!');
        }

        return view('name-change.request');
    }

    public function submitRequest(Request $request)
    {
        if (auth()->user()->hasActiveNameChangeRequest()) {
            return redirect()->route('dashboard')
                ->with('error', 'Már van egy folyamatban lévő névváltási kérelmed!');
        }

        $request->validate([
            'requested_name' => 'required|string|min:3|max:30|unique:users,charactername',
            'reason' => 'required|string|min:10|max:500'
        ], [
            'requested_name.required' => 'Az új név megadása kötelező!',
            'requested_name.min' => 'Az új névnek legalább 3 karakterből kell állnia!',
            'requested_name.max' => 'Az új név nem lehet hosszabb 30 karakternél!',
            'requested_name.unique' => 'Ez a név már foglalt!',
            'reason.required' => 'Az indoklás megadása kötelező!',
            'reason.min' => 'Az indoklásnak legalább 10 karakterből kell állnia!',
            'reason.max' => 'Az indoklás nem lehet hosszabb 500 karakternél!'
        ]);

        $nameChangeRequest = NameChangeRequest::create([
            'user_id' => auth()->id(),
            'current_name' => auth()->user()->charactername,
            'requested_name' => $request->requested_name,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'A névváltási kérelmed sikeresen elküldve! Az adminok hamarosan elbírálják.');
    }

    public function adminIndex()
    {
        $this->authorize('manage-name-changes');

        $pendingRequests = NameChangeRequest::with(['user', 'processor'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedRequests = NameChangeRequest::with(['user', 'processor'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedRequests = NameChangeRequest::with(['user', 'processor'])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('name-change.admin', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    public function processRequest(Request $request, NameChangeRequest $nameChangeRequest)
    {
        $this->authorize('manage-name-changes');

        if ($nameChangeRequest->status !== 'pending') {
            return back()->with('error', 'Ez a kérelem már el lett bírálva!');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'required|string|min:3|max:500'
        ]);

        $nameChangeRequest->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        if ($request->status === 'approved') {
            // Mentsük el a régi nevet
            PreviousName::create([
                'user_id' => $nameChangeRequest->user_id,
                'previous_name' => $nameChangeRequest->current_name,
                'changed_to' => $nameChangeRequest->requested_name,
                'name_change_request_id' => $nameChangeRequest->id,
                'changed_at' => now()
            ]);

            // Frissítsük a felhasználó nevét
            $nameChangeRequest->user->update([
                'charactername' => $nameChangeRequest->requested_name
            ]);

            // Értesítsük a felhasználót
            return back()->with('success', 'A névváltási kérelem elfogadva!');
        }

        return back()->with('success', 'A névváltási kérelem elutasítva!');
    }

    public function directNameChange(Request $request, $userId)
    {
        $this->authorize('manage-name-changes');

        $request->validate([
            'new_name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:users,charactername,' . $userId,
                'regex:/^[^0-9]+$/'
            ]
        ], [
            'new_name.required' => 'Az új név megadása kötelező!',
            'new_name.min' => 'Az új névnek legalább 3 karakterből kell állnia!',
            'new_name.max' => 'Az új név nem lehet hosszabb 30 karakternél!',
            'new_name.unique' => 'Ez a név már foglalt!',
            'new_name.regex' => 'A név nem tartalmazhat számokat!'
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $oldName = $user->charactername;

        // Névváltási kérelem létrehozása
        $nameChangeRequest = NameChangeRequest::create([
            'user_id' => $userId,
            'current_name' => $oldName,
            'requested_name' => $request->new_name,
            'reason' => 'Névváltási kérelmen kívül',
            'status' => 'approved',
            'admin_comment' => 'Leader által közvetlenül módosítva',
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        // Régi név mentése
        PreviousName::create([
            'user_id' => $userId,
            'previous_name' => $oldName,
            'changed_to' => $request->new_name,
            'name_change_request_id' => $nameChangeRequest->id,
            'changed_at' => now()
        ]);

        // Felhasználó nevének frissítése
        $user->update([
            'charactername' => $request->new_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'A név sikeresen módosítva!'
        ]);
    }
}
