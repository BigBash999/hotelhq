<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Scanner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                
                <!-- Style to force the video preview to fit nicely -->
                <style>
                    #reader { width: 100%; max-width: 400px; margin: 0 auto; }
                    #reader video { width: 100% !important; height: auto !important; border-radius: 8px; }
                    #reader__dashboard_section_csr button { padding: 8px 12px; background: #4f46e5; color: white; border-radius: 4px; }
                </style>

                <div id="reader" class="mx-auto border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 min-h-[300px]"></div>
                
                <div id="result" class="mt-6 text-xl font-bold"></div>

            </div>
        </div>
    </div>

    <div id="js-data" data-route="{{ route('attendance.scan') }}" data-token="{{ csrf_token() }}" style="display: none;"></div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const routeUrl = document.getElementById('js-data').getAttribute('data-route');
            const csrfToken = document.getElementById('js-data').getAttribute('data-token');
            const resultDiv = document.getElementById('result');

            if (typeof Html5QrcodeScanner === 'undefined') {
                resultDiv.innerHTML = '<span class="text-red-600">Error: QR Library not loaded. Did you run npm run build?</span>';
                return;
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false
            );

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear();
                resultDiv.innerHTML = `<span class="text-blue-500">Scanning... Please wait.</span>`;

                fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ qr_token: decodedText })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success) {
                        resultDiv.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                    } else {
                        resultDiv.innerHTML = `<span class="text-red-600">${data.message}</span>`;
                    }
                    
                    setTimeout(() => {
                        resultDiv.innerHTML = '';
                        html5QrcodeScanner.render(onScanSuccess);
                    }, 3000);
                })
                .catch(error => {
                    alert("SERVER ERROR:\n\n" + error.message);
                    resultDiv.innerHTML = `<span class="text-red-600">Check the popup for the error.</span>`;
                    setTimeout(() => {
                        resultDiv.innerHTML = '';
                        html5QrcodeScanner.render(onScanSuccess);
                    }, 3000);
                });
            }

            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</x-app-layout>