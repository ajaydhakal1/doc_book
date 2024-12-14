<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Reviews
     */
    public function index(Review $review)
    {
        $this->authorize('index', $review);
        $reviews = Review::all();
        return response()->json([
            'data' => $reviews
        ]);
    }

    /**
     * Create Review
     */
    public function store(Request $request, Review $review)
    {
        $this->authorize('store', $review);

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'comment' => 'required|string', // Match the form field and database column name
            'pdf' => 'nullable|file', // Validate the file for security
        ]);

        // Save review data
        $review = new Review();
        $review->appointment_id = $request->appointment_id;
        $review->comment = $request->comment;

        // Handle file upload
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('reviews', 'public');
            $review->pdf_path = $path;
        }

        $review->save();
        return response()->json([
            'message' => 'Review created successfully',
            'data' => $review
        ], 201);

    }

    /**
     * Show Review
     */
    public function show(Review $review)
    {
        $this->authorize('show', $review);

        return response()->json([
            'data' => $review
        ]);
    }

    /**
     * Update Review.
     */
    public function update(Request $request, string $id, Review $review)
    {
        $this->authorize('update', $review);

        $request->validate([
            'comment' => 'required|string',
            'pdf' => 'nullable|file', // Validate PDF
        ]);

        $review = Review::findOrFail($id);
        $review->comment = $request->comment;

        // Handle file upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF if it exists
            if ($review->pdf_path) {
                Storage::delete('public/' . $review->pdf_path);
            }
            $path = $request->file('pdf')->store('reviews', 'public');
            $review->pdf_path = $path;
        }

        $review->save();
        return response()->json([
            'message' => 'Review updated successfully',
            'data' => $review
        ], 200);
    }

    /**
     * Delete Review
     */
    public function destroy(Review $review)
    {
        $this->authorize('desroy', $review);

        $review->delete();
        return response()->json([
            'message' => 'Review deleted successfully'
        ], 200);
    }
}
