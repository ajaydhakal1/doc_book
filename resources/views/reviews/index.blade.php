<x-app-layout>
    <div class="container mx-auto px-4 py-8 min-h-screen bg-gray-900 text-gray-100">
        <div class="max-w-4xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold mb-6 text-center">Manage Reviews</h1>

            <!-- Add Review Button -->
            <a href="{{ route('reviews.create') }}"
                class="mb-6 inline-block bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-500 transition duration-300">
                Add New Review
            </a>

            <!-- Reviews Table -->
            <div class="overflow-x-auto bg-gray-700 rounded-lg shadow-lg">
                <table class="min-w-full bg-gray-800 text-gray-100 table-auto rounded-lg">
                    <thead class="bg-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Appointment Details</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Comment</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">PDF</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4 text-sm">
                                    <strong class="text-gray-200">Doctor:</strong>
                                    {{ $review->appointment->doctor->user->name }}<br>
                                    <strong class="text-gray-200">Date:</strong> {{ $review->appointment->date }}<br>
                                    <strong class="text-gray-200">Time:</strong>
                                    {{ \Carbon\Carbon::parse($review->appointment->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($review->appointment->end_time)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm">{{ Str::limit($review->comment, 50) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($review->pdf_path)
                                        <a href="{{ Storage::url($review->pdf_path) }}" target="_blank"
                                            class="text-blue-400 hover:underline">
                                            Download PDF
                                        </a>
                                    @else
                                        <span class="text-gray-500">No PDF</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('reviews.show', $review->id) }}"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition duration-300 mr-2">
                                        View
                                    </a>
                                    <a href="{{ route('reviews.edit', $review->id) }}"
                                        class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-500 transition duration-300 mr-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition duration-300">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
