@if (session('success'))
    <div id="success-message"
        class="w-full py-4 px-6 mb-4 flex items-center justify-between bg-green-300 border-l-4 border-green-500 rounded-lg relative backdrop-blur-sm transition-all duration-300 animate-fade-in">
        <div class="flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="text-black font-medium">Success</h3>
                <p class="text-black text-sm">{{ session('success') }}</p>
            </div>
        </div>
        <button onclick="closeMessage('success-message')"
            class="p-1 rounded-full hover:bg-green-800/50 transition-colors duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 group-hover:text-green-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif

@if (session('error'))
    <div id="error-message"
        class="w-full py-4 px-6 mb-4 flex items-center justify-between bg-red-400 border-l-4 border-red-500 rounded-lg relative backdrop-blur-sm transition-all duration-300 animate-fade-in">
        <div class="flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="text-white font-medium">Error</h3>
                <p class="text-white text-sm">{{ session('error') }}</p>
            </div>
        </div>
        <button onclick="closeMessage('error-message')"
            class="p-1 rounded-full hover:bg-red-800/50 transition-colors duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 group-hover:text-red-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<script>
    function closeMessage(id) {
        const element = document.getElementById(id);
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            element.style.display = 'none';
        }, 300);
    }

    // Auto-hide messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const messages = document.querySelectorAll('[id$="-message"]');
        messages.forEach(message => {
            setTimeout(() => {
                if (message) {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 300);
                }
            }, 5000);
        });
    });
</script>