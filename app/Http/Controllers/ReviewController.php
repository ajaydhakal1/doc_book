<?php

namespace App\Http\Controllers;

use App\Models\PatientHistory;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller implements HasMiddleware
{


    public static function middleware()
    {
        return [
            new Middleware('permission:view reviews', only: ['index']),
            new Middleware('permission:give reviews', only: ['store']),
            new Middleware('permission:edit reviews', only: ['edit']),
            new Middleware('permission:delete reviews', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'comment' => 'required|string', // Match the form field and database column name
            'pdf' => 'nullable|file', // Validate the file for security
        ]);

        // Save review data
        $review = new Review();
        $review->appointment_id = $request->appointment_id;
        $review->comment = $request->comment; // Use 'comment' as per the database column name

        // Handle file upload
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('reviews', 'public');
            $review->pdf_path = $path;
        }

        $review->save();

        // Link the review to the patient history
        $appointment = $review->appointment_id;
        $patientHistory = PatientHistory::where('appointment_id', $appointment)->first();

        if ($patientHistory) { // Check if a record exists
            $patientHistory->update([
                'review_id' => $review->id,
            ]);
        }

        return redirect()->route('my-appointments')->with('success', 'Review submitted successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:2048', // Validate PDF
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

        return redirect()->route('my-appointments')->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Delete the associated PDF if it exists
        if ($review->pdf_path) {
            Storage::delete('public/' . $review->pdf_path);
        }

        $review->delete();

        return redirect()->route('my-appointments')->with('success', 'Review deleted successfully.');
    }

}
