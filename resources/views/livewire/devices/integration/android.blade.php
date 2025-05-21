<div>
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Android SDK Integration</h2>
    <div class="mb-4">
        <a href="#" class="text-indigo-600 dark:text-indigo-400 underline font-medium">Download Android SDK</a>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Setup Instructions</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto">
// build.gradle
implementation 'com.signagesaas:sdk:1.0.0'

// AndroidManifest.xml
<uses-permission android:name="android.permission.INTERNET" />
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Code Sample</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
SignageSaasClient client = new SignageSaasClient.Builder()
    .setApiUrl("https://api.your-signagesaas.com")
    .setTenantId("your-tenant-id")
    .setContext(context)
    .build();

DeviceInfo info = new DeviceInfo.Builder()
    .setName("Lobby Display")
    .setType("android")
    .setHardwareId("ANDROID-1234567890")
    .setIpAddress("192.168.1.102")
    .setScreenResolution("1920x1080")
    .setOsVersion(Build.VERSION.RELEASE)
    .setAppVersion("1.0.0")
    .build();

client.registerDevice(info, new RegisterCallback() {
    @Override
    public void onSuccess(String token) {
        client.syncContent();
        client.startHeartbeatService(30);
        client.checkForUpdates();
    }
    @Override
    public void onError(SignageSaasException e) {
        // Handle error
    }
});
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">API Endpoints</button>
        <div x-show="open" class="mt-2" x-transition>
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="px-2 py-1">Path</th>
                        <th class="px-2 py-1">Method</th>
                        <th class="px-2 py-1">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="px-2 py-1">/device/register</td><td class="px-2 py-1">POST</td><td class="px-2 py-1">Register/authenticate device</td></tr>
                    <tr><td class="px-2 py-1">/device/heartbeat</td><td class="px-2 py-1">POST</td><td class="px-2 py-1">Send heartbeat/status</td></tr>
                    <tr><td class="px-2 py-1">/content/sync</td><td class="px-2 py-1">GET</td><td class="px-2 py-1">Sync content</td></tr>
                    <tr><td class="px-2 py-1">/device/ota</td><td class="px-2 py-1">GET</td><td class="px-2 py-1">Check for OTA updates</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Device Registration Token</label>
        <div class="flex items-center space-x-2">
            <input type="text" readonly value="{{ $tenantToken }}" class="w-64 px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs">
            <button x-data="{}" @click="$clipboard('{{ $tenantToken }}')" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded">Copy</button>
        </div>
        <div class="mt-2">
            {{-- {!! QrCode::size(96)->backgroundColor(31,41,55)->color(255,255,255)->generate($tenantToken) !!} --}}
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Troubleshooting & FAQ</button>
        <div x-show="open" class="mt-2" x-transition>
            <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-200">
                <li>Ensure your device has internet access and correct time settings.</li>
                <li>Check API URL and tenant ID for typos.</li>
                <li>Contact support at <a href="mailto:support@signagesaas.com" class="underline">support@signagesaas.com</a></li>
            </ul>
        </div>
    </div>
</div> 