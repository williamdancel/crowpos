<x-layouts::auth.simple :title="$title ?? null">
    {{ $slot }}
    <div class=" text-center text-xs">
        Powered by {{config('app.name')}} © {{ date('Y') }}
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="rounded-xl bg-white px-6 py-5 text-center shadow">
            <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-gray-200 border-t-gray-800"></div>
            <div class="mt-3 text-sm font-medium text-gray-800">Processing...</div>
        </div>
    </div>

     <script>
        (function () {
            const overlay = document.getElementById('loadingOverlay');
            if (!overlay) return;

            // Show overlay when any form on auth pages is submitted
            document.addEventListener('submit', function (e) {
                const form = e.target;

                // Optional: only forms inside auth layout
                const isAuthForm = form && form.closest('[data-auth-form], .auth-form, form');
                if (!isAuthForm) return;

                // If the browser blocks submit due to invalid fields, overlay won't show
                if (typeof form.checkValidity === 'function' && !form.checkValidity()) return;

                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }, true);

            // If user goes back or page is restored from bfcache, hide it again
            window.addEventListener('pageshow', function () {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            });
        })();
    </script>
</x-layouts::auth.simple>
