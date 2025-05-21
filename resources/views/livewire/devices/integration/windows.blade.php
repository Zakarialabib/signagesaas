<div>
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Windows SDK Integration</h2>
    <div class="mb-4">
        <a href="#" class="text-indigo-600 dark:text-indigo-400 underline font-medium">Download Windows SDK</a>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Setup Instructions</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto">
// .NET 6.0+
// Add SDK source files to your project
// Or install NuGet package (coming soon)
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Code Sample</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
using SignageSaaS;
using SignageSaaS.Models;

var client = new SignageSaasClient(apiUrl: "https://api.your-signagesaas.com", tenantId: "your-tenant-id");

var info = new DeviceInfo
{
    Name = "Lobby Display",
    Type = "windows",
    HardwareId = "WIN-1234567890",
    IpAddress = "192.168.1.101",
    ScreenResolution = "1920x1080",
    OsVersion = Environment.OSVersion.ToString(),
    AppVersion = "1.0.0"
};
await client.RegisterDeviceAsync(info);
await client.SyncContentAsync();
client.StartHeartbeatService(intervalSeconds: 30);
await client.CheckForUpdatesAsync();
client.StopHeartbeatService();
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