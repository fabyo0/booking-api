<?php

declare(strict_types=1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureJsonResources();
        $this->configureModels();
        $this->configurePasswordValidation();
        $this->configureApiDocumentation();
        $this->configureRateLimiting();
        $this->configurePermissionsCache();
    }

    private function configurePasswordValidation(): void
    {
        Password::defaults(fn() => $this->app->isProduction()
            ? Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : Password::min(6));
    }

    private function configureModels(): void
    {
        if ($this->app->environment('local', 'testing')) {
            DB::listen(function ($query): void {
                if (str_contains($query->sql, 'select * from')) {
                    $backtrace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10))
                        ->filter(fn($trace): bool => isset($trace['file']) &&
                            ! str_contains((string) $trace['file'], '/vendor/') &&
                            ! str_contains((string) $trace['file'], '/framework/'))
                        ->first();

                    $location = isset($backtrace)
                        ? basename($backtrace['file']) . ':' . $backtrace['line']
                        : 'Unknown location';

                    logger()->info(
                        "Possible N+1 query in {$location}: {$query->sql}",
                        ['bindings' => $query->bindings, 'time' => $query->time, 'caller' => $location],
                    );
                }
            });
        }
    }

    private function configureJsonResources(): void
    {
        JsonResource::withoutWrapping();
    }

    private function configureApiDocumentation(): void
    {
        Scramble::extendOpenApi(function (OpenApi $openApi): void {
            $openApi->secure(
                SecurityScheme::http('bearer'),
            );
        });
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for(
            'global',
            fn(Request $request) => Limit::perMinute(60)->by($request->ip()),
        );

        RateLimiter::for(
            'api',
            fn(Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()),
        );

        RateLimiter::for(
            'auth',
            fn(Request $request) => Limit::perMinute(5)->by($request->ip()),
        );
    }

    private function configurePermissionsCache(): void
    {
        App::make(\Spatie\Permission\PermissionRegistrar::class)->initializeCache();
    }

}
