<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('property')->latest();

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('approved', true);
            } else {
                $query->where('approved', false);
            }
        }

        $reviews = $query->paginate(20)->withQueryString();

        return view('reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['approved' => true]);

        toastr()->success('Review approved.');

        return back();
    }

    public function destroy(Review $review)
    {
        $review->delete();

        toastr()->success('Review deleted.');

        return back();
    }
}
