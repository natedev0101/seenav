namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersionUpdate extends Model
{
    protected $fillable = ['version', 'color', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}