@if (session('success'))
    <div id="success-message"
        class="w-full py-4 flex justify-between items-center bg-green-300 border border-green-600 rounded-lg relative px-4">
        <h3>{{ session('success') }}</h3>
        <button onclick="closeMessage('success-message')"
            class="absolute top-1/2 transform -translate-y-1/2 right-4 text-3xl font-bold text-green-600 hover:text-green-800">
            &times;
        </button>
    </div>
@endif

@if (session('error'))
    <div id="error-message" class="w-full py-4 flex justify-between items-center bg-red-500 rounded-lg relative px-4">
        <h3 class="text-white">{{ session('error') }}</h3>
        <button onclick="closeMessage('error-message')"
            class="absolute top-1/2 transform -translate-y-1/2 right-4 text-3xl font-bold text-white hover:text-gray-200">
            &times;
        </button>
    </div>
@endif

<script>
    // Function to hide the message
    function closeMessage(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>