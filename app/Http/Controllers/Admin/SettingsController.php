<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    /**
     * Constructor to apply authorization.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if the user has admin permission
     */
    private function checkAdminPermission()
    {
        if (! Gate::allows('admin')) {
            abort(403, 'لا تملك صلاحية الوصول لهذه الصفحة.');
        }
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->checkAdminPermission();
        return view('admin.settings');
    }

    /**
     * Update the application settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->checkAdminPermission();
        
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'mail_from_name' => 'required|string|max:255',
            'mail_from_address' => 'required|email',
        ]);

        // Update .env file
        $this->updateEnvironmentFile([
            'APP_NAME' => '"' . $request->app_name . '"',
            'APP_URL' => $request->app_url,
            'APP_DEBUG' => $request->has('app_debug') ? 'true' : 'false',
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
        ]);

        // Clear cache
        Artisan::call('config:clear');
        
        return redirect()->route('admin.settings')->with('success', 'تم تحديث الإعدادات بنجاح!');
    }

    /**
     * Update the environment file with the given values.
     *
     * @param  array  $values
     * @return void
     */
    protected function updateEnvironmentFile($values)
    {
        $envFile = app()->environmentFilePath();
        $contentArray = file($envFile, FILE_IGNORE_NEW_LINES);

        foreach ($values as $key => $value) {
            foreach ($contentArray as $lineNum => $line) {
                // If the line contains the key, replace it
                if (strpos($line, "{$key}=") !== false) {
                    $contentArray[$lineNum] = "{$key}={$value}";
                    break;
                }
            }
        }

        $content = implode("\n", $contentArray);
        file_put_contents($envFile, $content);
    }
} 