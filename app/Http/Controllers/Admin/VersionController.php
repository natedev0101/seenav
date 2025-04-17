namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VersionUpdate;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function getVersions()
    {
        return VersionUpdate::all();
    }

    public function updateVersion($id)
    {
        // Deactivate all versions
        VersionUpdate::query()->update(['is_active' => false]);

        // Activate the selected version
        $version = VersionUpdate::findOrFail($id);
        $version->update(['is_active' => true]);

        return back()->with('success', 'Verzió sikeresen frissítve!');
    }

    public function getCurrentVersion()
    {
        return VersionUpdate::where('is_active', true)->first();
    }
}