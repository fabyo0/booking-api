<?php

declare(strict_types=1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureJsonResources();
        $this->configureModels();
        $this->configurePasswordValidation();
        $this->configureApiDocumentation();
    }

    private function configurePasswordValidation(): void
    {
        Password::defaults(function () {
            return $this->app->isProduction()
                ? Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : Password::min(6);
        });
    }

    private function configureModels(): void
    {
        Model::unguard();
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
}
