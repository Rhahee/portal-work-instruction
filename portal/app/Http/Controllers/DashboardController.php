<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\WorkInstruction;

class DashboardController extends Controller
{
    public function __invoke(\Illuminate\Http\Request $request)
    {
        $baseQuery = WorkInstruction::query()->when(! $request->user()->isAdmin(), fn ($q) => $q->where('author_id', $request->user()->id));
        return view('dashboard.index', [
            'totalInstructions' => (clone $baseQuery)->count(), 'publishedInstructions' => (clone $baseQuery)->published()->count(),
            'draftInstructions' => (clone $baseQuery)->where('status', 'draft')->count(), 'pendingReviewInstructions' => (clone $baseQuery)->where('status', 'pending_review')->count(), 'rejectedInstructions' => (clone $baseQuery)->where('status', 'rejected')->count(), 'totalCategories' => Category::count(),
            'totalUsers' => User::count(), 'recentInstructions' => (clone $baseQuery)->with(['category', 'author'])->latest()->take(5)->get(),
        ]);
    }
}
