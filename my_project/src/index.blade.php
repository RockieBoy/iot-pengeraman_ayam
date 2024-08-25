<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kandang Ayam</title>
  @vite('resources/css/app.css')
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    {{-- background blur --}}
    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
      </div>
      <div class="absolute inset-x-0 overflow-hidden -top-40 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
      </div>
      {{-- background blur --}}
<div class="container p-8 mx-auto">

    <h1 class="mb-8 text-2xl font-bold text-center uppercase ">Pemantauan Kandang Ayam</h1>
    <!-- Temperature Display and Chart -->
    <div class="flex flex-col w-full gap-4 lg:flex-row ">
        <div class="w-full p-6 mb-8 bg-white rounded-lg shadow-lg lg:w-1/2">
            <h2 class="mb-4 text-xl font-bold">Suhu Saat Ini</h2>
            <div id="temperature" class="mb-4 text-3xl font-bold text-red-600">-- °C</div>
            <canvas id="temperatureChart"></canvas>
        </div>
        <div class="w-full p-6 mb-8 bg-white rounded-lg shadow-lg lg:w-1/2">
            <h2 class="mb-4 text-xl font-bold">Kelembapan Saat Ini</h2>
            <div class="flex ">
                <p id="humidity" class="mb-4 text-3xl font-bold text-red-600">-- %</p>
                <!-- <img src="./assets/humidity.png" width="50" height="50" alt="" srcset=""> -->
            </div>
            <canvas id="humidityChart"></canvas>
        </div>
    </div>
    <!-- Fan and Lamp Control -->
<div class="p-6 mb-8 bg-white rounded-lg shadow-lg">
    <h2 class="mb-4 text-xl font-bold">Control Kipas dan Lampu</h2>
    <div class="flex items-center mb-4">
        <span class="mr-3">Kipas</span>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="fan-toggle" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-lg-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        </label>
    </div>
    <div class="flex items-center mb-4">
        <span class="mr-3">Lampu</span>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="lamp-toggle" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-lg-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        </label>
    </div>
</div>

<!-- Fan Confirmation Modal -->
<div id="fan-popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full p-4">
        <div class="relative bg-white shadow rounded-lg-lg dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="fan-popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 text-center md:p-5">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin mematikan kipas?</h3>
                <button id="confirm-turn-off-fan" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, matikan
                </button>
                <button type="button" data-modal-hide="fan-popup-modal" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak, batalkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Lamp Confirmation Modal -->
<div id="lamp-popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full p-4">
        <div class="relative bg-white shadow rounded-lg-lg dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="lamp-popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 text-center md:p-5">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin mematikan lampu?</h3>
                <button id="confirm-turn-off-lamp" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Ya, matikan
                </button>
                <button type="button" data-modal-hide="lamp-popup-modal" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak, batalkan</button>
            </div>
        </div>
    </div>
</div>

<div class="p-6 mt-8 bg-white rounded-lg shadow-lg">
    <button id="set-timer" class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-700">Ayam</button>
</div>


    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/paho-mqtt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // Initialize variables for chart data
        const tempLabels = [];
        const temperatureData = [];
        const humidityLabels = [];
        const humidityData = [];

        // Connect to the MQTT broker using Paho MQTT
        const client = mqtt.connect('wss://public.cloud.shiftr.io:443/mqtt', {
            username: "public",
            password: "public"
        });

        // Log when the client connects
        client.on('connect', () => {
            console.log('Connected to MQTT broker');
            client.subscribe('kandang_ayam/temperature');
            client.subscribe('kandang_ayam/humidity');
            client.subscribe('kandang_ayam/fan');
            client.subscribe('kandang_ayam/lamp');
            client.subscribe('kandang_ayam/timer');
        });

        // Log incoming messages and update the UI accordingly
        client.on('message', (topic, message) => {
            const messageString = message.toString();
            console.log(`Message received on ${topic}: ${messageString}`);

            const now = new Date().toLocaleTimeString();

            if (topic === 'kandang_ayam/temperature') {
                const temperature = parseFloat(messageString);
                if (!isNaN(temperature)) {
                    document.getElementById('temperature').textContent = temperature.toFixed(1) + ' °C';
                    updateChart(temperatureChart, tempLabels, temperatureData, temperature, now);
                }
            }

            if (topic === 'kandang_ayam/humidity') {
                const humidity = parseFloat(messageString);
                if (!isNaN(humidity)) {
                    document.getElementById('humidity').textContent = humidity.toFixed(1) + ' %';
                    updateChart(humidityChart, humidityLabels, humidityData, humidity, now);
                }
            }

            if (topic === 'kandang_ayam/fan') {
                document.getElementById('fan-toggle').checked = (messageString === 'on');
            }

            if (topic === 'kandang_ayam/lamp') {
                document.getElementById('lamp-toggle').checked = (messageString === 'on');
            }

            if (topic === 'kandang_ayam/timer') {
                console.log(`Timer setting received: ${messageString}`);
            }
        });

        // Log errors
        client.on('error', (error) => {
            console.error('MQTT Error:', error);
        });

        // Log when connection is lost
        client.on('disconnect', () => {
            console.log('Connection Lost');
        });

        // Handle fan toggle change
        document.getElementById('fan-toggle').addEventListener('change', (e) => {
            const message = e.target.checked ? 'on' : 'off';
            client.publish('kandang_ayam/fan', message, { qos: 1 });
            console.log(`Fan command sent: ${message}`);
        });

        // Handle lamp toggle change
        document.getElementById('lamp-toggle').addEventListener('change', (e) => {
            const message = e.target.checked ? 'on' : 'off';
            client.publish('kandang_ayam/lamp', message, { qos: 1 });
            console.log(`Lamp command sent: ${message}`);
        });

        // Set timer
        document.getElementById('set-timer').addEventListener('click', () => {
            const time = document.getElementById('timer').value;
            client.publish('kandang_ayam/timer', time, { qos: 1 });
            console.log(`Timer set command sent: ${time}`);
            alert('Timer set to ' + time);
        });

        // Function to update chart
        function updateChart(chart, labels, data, value, label) {
            // Ensure chart has only the latest 10 data points
            if (data.length >= 10) {
                data.shift();
                labels.shift();
            }
            labels.push(label);
            data.push(value);
            chart.update();
        }

        // Initialize temperature chart
        const temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
        const temperatureChart = new Chart(temperatureCtx, {
            type: 'line',
            data: {
                labels: tempLabels,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: temperatureData,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Initialize humidity chart
        const humidityCtx = document.getElementById('humidityChart').getContext('2d');
        const humidityChart = new Chart(humidityCtx, {
            type: 'line',
            data: {
                labels: humidityLabels,
                datasets: [{
                    label: 'Humidity (%)',
                    data: humidityData,
                    borderColor: 'rgb(153, 102, 255)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>







</body>
</html>
