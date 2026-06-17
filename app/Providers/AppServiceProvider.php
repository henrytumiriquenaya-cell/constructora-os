<?php

namespace App\Providers;

use App\Models\LogCambio;
use App\Models\User;
use App\Models\Usuario;
use App\Observers\AuditObserver;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        
        $this->registerAuditObservers();
        $this->registerBladePermissions();
        $this->registerViewComposers();
    }

        protected function registerAuditObservers(): void
    {
        // 🧠 Lista de tablas de sistema que JAMÁS deben ser observadas para evitar bucles o duplicados
        $tablasExcluidas = [
            'log_cambios',
            'notificaciones',
            'failed_jobs',
            'migrations',
            'sessions',
        ];

        $path = app_path('Models');
        foreach (File::files($path) as $file) {
            $class = 'App\\Models\\'.pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if (! class_exists($class)) {
                continue;
            }

            if (is_subclass_of($class, Model::class)) {
                $instance = new $class;
                $tableName = $instance->getTable();

                if (str_contains($tableName, '.')) {
                    $parts = explode('.', $tableName);
                    $tableName = end($parts);
                }

                if (in_array(strtolower($tableName), $tablasExcluidas, true)) {
                    continue;
                }

                $class::observe(\App\Observers\AuditObserver::class);
            }
        }
    }


    protected function registerBladePermissions(): void
    {
        Blade::if('canAccess', function (string $table, string $operation = 'S') {
            $user = Auth::user();
            if (! $user) {
                return false;
            }

            return app(PermissionService::class)->can($user, $table, $operation);
        });
    }

    protected function registerViewComposers(): void
    {
        View::composer(['layouts.app', 'dashboard', 'operativa.*', 'rrhh.*'], function ($view) {
            $user = Auth::user();
            $permissions = app(PermissionService::class);

            $view->with([
                'authUser' => $user,
                'perm' => $permissions,
                'menuOperativa' => config('permissions.menu', []),
                'menuRrhh' => config('permissions.menu_rrhh', []),
                'showOperativa' => $permissions->showOperativaMenu($user),
                'showRrhh' => $permissions->showRrhhMenu($user),
            ]);
        });
    }
}
