<x-app-layout>
    <div class="container mx-auto px-4 py-8 min-h-screen bg-gray-900 text-gray-100">
        <div class="max-w-4xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Review Details</h1>
            <p class="mb-2"><strong>Comment:</strong> {{ $review->comment }}</p>
            @if ($review->pdf_path)
                <p>
                    <strong>PDF:</strong>
                    <a href="{{ Storage::url($review->pdf_path) }}" target="_blank" class="text-blue-400 hover:underline">
                        Download PDF
                    </a>
                </p>
            @endif
            <div class="mt-4">
                <a href="{{ route('reviews.edit', $review->id) }}"
                    class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-500">
                    Edit Review
                </a>
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                        Delete Review
                    </button>
                </form>
            </div>
            <a href="{{ route('my-appointments') }}"
                class="mt-4 inline-block bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">
                Back to Appointments
            </a>
        </div>
    </div>
</x-app-layout>
