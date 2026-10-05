<?php

namespace App\Providers;

use App\Models\WorkInstruction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
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
        View::composer('components.layouts.app', function ($view): void {
            $user = auth()->user();

            if (! $user?->canSubmitInstructions()) {
                $view->with('workspaceAttentionCount', 0);

                return;
            }

            $workspaceAttentionCount = WorkInstruction::query()
                ->when(
                    $user->isAdmin(),
                    fn ($query) => $query->where('status', 'pending_review')
                        ->orWhere('deletion_status', 'pending'),
                    fn ($query) => $query->where('author_id', $user->id)
                        ->where('status', 'rejected'),
                )
                ->count();

            $view->with('workspaceAttentionCount', $workspaceAttentionCount);
        });
    }
}
