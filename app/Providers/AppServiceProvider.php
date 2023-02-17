<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Filament::serving(static function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label(__('Users'))
                    ->icon('heroicon-o-users')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Pages'))
                    ->icon('heroicon-o-document-duplicate')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Players'))
                    ->icon('heroicon-o-play')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Chats'))
                    ->icon('heroicon-o-chat')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Timetables'))
                    ->icon('heroicon-o-calendar')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Quizzes'))
                    ->icon('heroicon-o-question-mark-circle')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Shop'))
                    ->icon('heroicon-o-shopping-bag')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Articles'))
                    ->icon('heroicon-o-document-text')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Export'))
                    ->icon('heroicon-o-archive')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(__('Media'))
                    ->icon('heroicon-o-photograph')
                    ->collapsed(),
            ]);
        });
    }
}
