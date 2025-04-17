<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\DutyTime;
use App\Models\Report;
use App\Models\Rank;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'charactername',
        'username',
        'email',
        'password',
        'isAdmin',
        'canGiveAdmin',
        'is_superadmin',
        'character_id',
        'played_minutes',
        'phone_number',
        'badge_number',
        'recommended_by',
        'last_active',
        'current_page',
        'time_spent',
        'is_online',
        'rank_id',
        'rank',
        'subdivision_ids',
        'last_rank_change',
        'is_on_duty',
        'service_start',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_required',
        'name',
        'approved_reports_count',
        'pending_reports_count',
        'rejected_reports_count',
        'status',
        'plus_points',
        'medal'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_active' => 'datetime',
        'isAdmin' => 'boolean',
        'is_superadmin' => 'boolean',
        'is_officer' => 'boolean',
        'time_spent' => 'integer',
        'two_factor_required' => 'boolean',
        'last_rank_change' => 'datetime',
        'is_on_duty' => 'boolean',
        'service_start' => 'datetime',
        'approved_reports_count' => 'integer',
        'pending_reports_count' => 'integer',
        'rejected_reports_count' => 'integer',
        'status' => 'string',
        'plus_points' => 'integer'
    ];

    public function getIsOfficerAttribute()
    {
        return $this->attributes['is_officer'] ?? false;
    }

    public function getFormattedTimeSpentAttribute()
    {
        $hours = floor($this->time_spent / 60); // Órák
        $minutes = $this->time_spent % 60; // Maradék percek
    
        return sprintf('%d óra %d perc', $hours, $minutes);
    }

    public function getIsOnlineAttribute()
    {
        if (!$this->last_active) {
            return false;
        }
        
        // Az inaktivitási időkorlát (percben) - a session lifetime-ot használjuk
        $inactivityThreshold = config('session.lifetime', 120);
        
        // Ellenőrizzük, hogy az utolsó aktivitás óta eltelt idő kisebb-e, mint az inaktivitási időkorlát
        return now()->diffInMinutes($this->last_active) < $inactivityThreshold;
    }

    public function getIsAdminAttribute($value)
    {
        return (bool) $value;
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    public function subdivisions()
    {
        return $this->belongsToMany(Subdivision::class, 'subdivision_user');
    }

    public function likedAnnouncements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_likes');
    }

    public function heartedAnnouncements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_hearts');
    }

    public function warnings()
    {
        return $this->hasMany(Warning::class);
    }

    public function dashboardPreference()
    {
        return $this->hasOne(DashboardPreference::class, 'user_id', 'id');
    }

    public function isAdmin()
    {
        return $this->attributes['isAdmin'] || $this->attributes['is_superadmin'];
    }

    public function nameChangeRequests()
    {
        return $this->hasMany(NameChangeRequest::class);
    }

    public function previousNames()
    {
        return $this->hasMany(PreviousName::class);
    }

    public function processedNameChangeRequests()
    {
        return $this->hasMany(NameChangeRequest::class, 'processed_by');
    }

    public function hasActiveNameChangeRequest()
    {
        return $this->nameChangeRequests()->where('status', 'pending')->exists();
    }

    /**
     * Get all case reports for the user.
     */
    public function caseReports()
    {
        return $this->hasMany(CaseReport::class);
    }

    /**
     * Get all service logs for the user.
     */
    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }

    /**
     * Az olvasatlan hírek száma a felhasználónak.
     */
    public function unreadNewsCount()
    {
        return News::where('archived', false)
            ->whereDoesntHave('views', function($query) {
                $query->where('user_id', $this->id);
            })
            ->count();
    }

    /**
     * A felhasználó által megtekintett hírek.
     */
    public function viewedNews()
    {
        return $this->belongsToMany(News::class, 'user_news_views')
            ->withTimestamps()
            ->withPivot('viewed_at');
    }

    /**
     * A felhasználó rangváltozásainak lekérdezése.
     */
    public function rank_changes()
    {
        return $this->hasMany(RankChange::class);
    }

    public function activeDuty()
    {
        return $this->hasOne(DutyTime::class)
            ->where('is_weekly_closed', false)
            ->whereNull('ended_at')
            ->latest('started_at');
    }

    public function dutyTimes()
    {
        return $this->hasMany(DutyTime::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function partnerReports()
    {
        return $this->belongsToMany(Report::class, 'report_partners', 'partner_id', 'report_id');
    }
}
