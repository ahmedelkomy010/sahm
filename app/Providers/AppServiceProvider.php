<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // تسجيل FileHelper
        $this->app->singleton('filehelper', function () {
            return new \App\Helpers\FileHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إعداد HTTPS للخادم العالمي
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        // إعداد المجلدات للتأكد من وجودها
        $uploadPaths = [
            storage_path('app/public/uploads'),
            storage_path('app/public/uploads/work_orders'),
        ];
        
        foreach ($uploadPaths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }

        // إضافة البلايد directives للملفات
        \Blade::directive('imageUrl', function ($expression) {
            return "<?php echo \App\Helpers\FileHelper::getImageUrl($expression); ?>";
        });
        
        \Blade::directive('fileIcon', function ($expression) {
            return "<?php echo \App\Helpers\FileHelper::getFileIcon($expression); ?>";
        });
        
        \Blade::directive('fileSize', function ($expression) {
            return "<?php echo \App\Helpers\FileHelper::formatFileSize($expression); ?>";
        });
    }
}
