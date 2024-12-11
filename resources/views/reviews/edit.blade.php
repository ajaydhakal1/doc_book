<x-app-layout>
    <div class="container mx-auto px-4 py-8 min-h-screen bg-gray-900 text-gray-100">
        <div class="max-w-4xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Edit Review</h1>
            <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-semibold mb-2">Comment</label>
                    <textarea name="comment" id="comment" rows="4"
                        class="w-full p-2 rounded bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comment', $review->comment) }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="pdf" class="block text-sm font-semibold mb-2">Upload PDF (optional)</label>
                    <input type="file" name="pdf" id="pdf"
                        class="w-full p-2 bg-gray-900 border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('my-appointments') }}"
                        class="bg-red-600 text-white px-4 py-2 rounded mr-2 hover:bg-red-500">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Update
                        Review</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
