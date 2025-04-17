<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Hamcrest\Type\IsInteger;
use App\Models\User;
use App\Models\Announcement;
use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;
use Emojione\Client as EmojiOneClient;
use Emojione\Ruleset;
use Illuminate\Support\Facades\Auth;
use App\Models\Rank;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /* private function getDiscordRoles()
    {
        $client = new Client([
            'headers' => [
                'Authorization' => env('DISCORD_BOT_TOKEN'),
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        try {
            $response = $client->get('https://discord.com/api/v10/guilds/' . env("DISCORD_GUILD_ID") . '/roles');

            $body = (string) $response->getBody();
            $utf8Body = mb_convert_encoding($body, 'UTF-8');
            $roles = json_decode($utf8Body, true);
    
            return $roles;
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function returnDiscordRole($roles, $id)
    {
        if ($roles == null) {
            return "(Nincs rang)";
        }

        $id_new = preg_replace("/[^0-9]/", "", $id);

        foreach ($roles as $role) {
            if ($role["id"] == $id_new) {
                return $role["name"];
            }
        }

        return null;
    }

    private function getDiscordUser($id)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => env('DISCORD_BOT_TOKEN'),
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        try {
            $response = $client->get('https://discord.com/api/v10/guilds/' . env("DISCORD_GUILD_ID") . '/members/' . $id);
            $body = (string) $response->getBody();
            $utf8Body = mb_convert_encoding($body, 'UTF-8');
            $user = json_decode($utf8Body, true);
    
            return $user;
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function returnDiscordUser($id)
    {
        $id_new = preg_replace("/[^0-9]/", "", $id);
        $user = $this->getDiscordUser($id_new);
        
        if ($user == null) {
            return "(Volt tag)";
        }

        return $user["nick"];
    }

    private function getDiscordChannel($id)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => env('DISCORD_BOT_TOKEN'),
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        try {
            $response = $client->get('https://discord.com/api/v10/channels/' . $id);

            $body = (string) $response->getBody();
            $utf8Body = mb_convert_encoding($body, 'UTF-8');
            $channel = json_decode($utf8Body, true);
    
            return $channel;
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function returnDiscordChannel($id)
    {
        $id_new = preg_replace("/[^0-9]/", "", $id);
        $channel = $this->getDiscordChannel($id_new);

        if ($channel == null) {
            return "(Nem létező channel)";
        }

        return $channel["name"];
    }

    private function getDiscordAnnouncements()
    {
        $client = new Client([
            'headers' => [
                'Authorization' => env('DISCORD_BOT_TOKEN'),
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        $messages = $client->get('https://discord.com/api/v10/channels/1225882367671931002/messages');
        $roles = $this->getDiscordRoles();

        $body = (string) $messages->getBody();
        $utf8Body = mb_convert_encoding($body, 'UTF-8');
        $messages = json_decode($utf8Body, true);

        $firstFiveMessages[][] = array();
        
        // I have no fucking idea how this works, but it does
        for ($i = 0; $i < 5; $i++) {
            $timestamp = new DateTime($messages[$i]["timestamp"]);
            $firstFiveMessages[$i]["time"] = $timestamp->format('Y-m-d H:i:s');
            $firstFiveMessages[$i]["author"] = $this->returnDiscordUser($messages[$i]["author"]["id"]);

            $words = explode(" ", $messages[$i]["content"]);

            foreach ($words as &$word) {
                $newline_explode = explode("\n", $word);
                foreach ($newline_explode as &$newline_explode_word) {
                    if (preg_match('/<@&[0-9]/', $newline_explode_word)) {
                        $newline_explode_word = "<i><b>@" . $this->returnDiscordRole($roles, $newline_explode_word) . "</b></i>";
                    }

                    if (preg_match('/<@[0-9]/', $newline_explode_word)) {
                        $newline_explode_word = "<i>@" . $this->returnDiscordUser($newline_explode_word) . "</i>";
                    }

                    if (preg_match('/<#[0-9]/', $newline_explode_word)) {
                        $newline_explode_word = "</i>#" . $this->returnDiscordChannel($newline_explode_word) . "</i>";
                    }
                }

                $word = implode("\n", $newline_explode);
            }

            $firstFiveMessages[$i]["message"] = implode(" ", $words);
            $firstFiveMessages[$i]["message"] = nl2br($firstFiveMessages[$i]['message']);

            if (count($messages[$i]["attachments"]) > 0) {
                for ($j = 0; $j < count($messages[$i]["attachments"]); $j++) { 
                    $firstFiveMessages[$i]["images"][$j] = $messages[$i]["attachments"][$j]["url"];
                }
            }
        }

        return $firstFiveMessages;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Online felhasználók lekérése az is_online mező alapján
        $onlineUsers = User::where('is_online', true)
            ->with(['rank']) // Előre betöltjük a rangokat a N+1 probléma elkerülésére
            ->orderBy('last_active', 'desc') // Rendezés utolsó aktivitás szerint
            ->get();

        return view('view_dashboard', [
            'user' => $user,
            'onlineUsers' => $onlineUsers,
            'announcements' => [], // Üres tömb, mivel eltávolítottuk a közleményeket
        ]);
    }

    /**
     * Dashboard beállítások frissítése
     */
    public function updatePreferences(Request $request)
    {
        try {
            $validated = $request->validate([
                'visible_cards' => 'required|array',
                'visible_cards.*' => 'string'
            ]);

            $user = Auth::user();
            
            if (!$user) {
                throw new \Exception('Felhasználó nem található');
            }

            $user->dashboard_preferences = $validated['visible_cards'];
            
            if (!$user->save()) {
                throw new \Exception('Nem sikerült menteni a beállításokat');
            }

            return response()->json([
                'message' => 'Beállítások sikeresen mentve',
                'visible_cards' => $user->dashboard_preferences
            ]);
        } catch (\Exception $e) {
            Log::error('Hiba a dashboard beállítások mentésekor: ' . $e->getMessage());
            Log::error('User ID: ' . (Auth::id() ?? 'null'));
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => 'Hiba történt a beállítások mentésekor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Megjeleníti a felhasználó statisztikáit
     */
    public function statistics()
    {
        $user = auth()->user();
        
        // Betöltjük a kapcsolódó adatokat
        $user->load(['rank', 'subdivisions', 'caseReports']);
        
        // Jelentések statisztikái
        $reportsStats = [
            'total' => $user->caseReports()->count(),
            'approved' => $user->approved_reports_count ?? 0,
            'rejected' => $user->rejected_reports_count ?? 0,
            'pending' => $user->pending_reports_count ?? 0
        ];

        return view('statistics', [
            'user' => $user,
            'reportsStats' => $reportsStats
        ]);
    }
}
