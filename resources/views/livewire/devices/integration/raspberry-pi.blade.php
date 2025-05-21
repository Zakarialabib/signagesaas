<div>
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Raspberry Pi SDK Integration</h2>
    <div class="mb-4">
        <a href="#" class="text-indigo-600 dark:text-indigo-400 underline font-medium">Download Python SDK</a>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Setup Instructions</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto">
# Install dependencies
pip install requests

# Clone or download the SDK
# git clone https://github.com/your-org/signagesaas-python-sdk.git
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Code Sample</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
from signagesaas.client import SignageSaasClient
from signagesaas.models import DeviceInfo

client = SignageSaasClient(api_url="https://api.your-signagesaas.com", tenant_id="your-tenant-id")

info = DeviceInfo(
    name="Lobby Display",
    type="raspberry-pi",
    hardware_id="RPI-1234567890",
    ip_address="192.168.1.100",
    screen_resolution="1920x1080",
    os_version="Raspbian 11",
    app_version="1.0.0"
)
token = client.register_device(info)

client.sync_content()
client.start_heartbeat_service(interval_seconds=30)
client.check_for_updates()
client.stop_heartbeat_service()
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