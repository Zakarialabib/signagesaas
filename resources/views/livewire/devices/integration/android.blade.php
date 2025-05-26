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
implementation 'com.squareup.retrofit2:retrofit:2.9.0'
implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
implementation 'com.squareup.okhttp3:okhttp:4.9.1'
implementation 'com.squareup.okhttp3:logging-interceptor:4.9.1'

// AndroidManifest.xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Constants Configuration</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
object Constants {
    // SharedPreferences
    const val SHARED_PREFS_NAME = "SignageProPrefs"
    const val PREF_AUTH_TOKEN = "auth_token"
    const val PREF_DEVICE_ID = "device_id"

    // Networking
    const val BASE_URL = "https://test.signagesaas.test/api/v1/"
    const val CONNECT_TIMEOUT_SECONDS = 30L
    const val READ_TIMEOUT_SECONDS = 30L
    const val WRITE_TIMEOUT_SECONDS = 30L

    // API Endpoints
    const val ENDPOINT_AUTHENTICATE = "authenticate"
    const val ENDPOINT_HEARTBEAT = "device/heartbeat/{deviceId}"
    const val ENDPOINT_SYNC = "device/sync/{deviceId}"
    const val ENDPOINT_DOWNLOAD = "device/download/{deviceId}"
    const val ENDPOINT_MEDIA = "device/media/{deviceId}/{contentId}"
    const val ENDPOINT_CONTENT = "content"
    const val ENDPOINT_SCREENS = "screens"

    // Other Constants
    const val MAX_CACHE_SIZE_MB = 200L
    const val HEARTBEAT_INTERVAL_MINUTES = 15L
    const val SYNC_CHECK_INTERVAL_MINUTES = 5L
}
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Code Sample</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
// Initialize the SDK
SignageSaasClient client = new SignageSaasClient.Builder()
    .setBaseUrl(Constants.BASE_URL)
    .setTenantId("your-tenant-id")
    .setContext(context)
    .setConnectTimeout(Constants.CONNECT_TIMEOUT_SECONDS)
    .setReadTimeout(Constants.READ_TIMEOUT_SECONDS)
    .setWriteTimeout(Constants.WRITE_TIMEOUT_SECONDS)
    .build();

// Register/Authenticate Device
DeviceInfo info = new DeviceInfo.Builder()
    .setHardwareId(DeviceUtils.getUniqueHardwareId(context))
    .setName(Build.MODEL)
    .setType("android")
    .setIpAddress(NetworkUtils.getIpAddress())
    .setScreenResolution(ScreenUtils.getResolution(context))
    .setOsVersion(Build.VERSION.RELEASE)
    .setAppVersion(BuildConfig.VERSION_NAME)
    .build();

client.authenticate(info, new AuthCallback() {
    @Override
    public void onSuccess(AuthResponse response) {
        // Store authentication token
        PreferenceManager.getDefaultSharedPreferences(context)
            .edit()
            .putString(Constants.PREF_AUTH_TOKEN, response.getToken())
            .putString(Constants.PREF_DEVICE_ID, response.getDeviceId())
            .apply();

        // Start services
        startHeartbeatService();
        startContentSyncService();
    }

    @Override
    public void onError(SignageSaasException e) {
        Log.e("SignageSaas", "Authentication failed", e);
    }
});

// Heartbeat Service
private void startHeartbeatService() {
    WorkManager.getInstance(context)
        .enqueueUniquePeriodicWork(
            "heartbeat",
            ExistingPeriodicWorkPolicy.REPLACE,
            new PeriodicWorkRequestBuilder<HeartbeatWorker>(
                Constants.HEARTBEAT_INTERVAL_MINUTES,
                TimeUnit.MINUTES
            ).build()
        );
}

// Content Sync Service
private void startContentSyncService() {
    WorkManager.getInstance(context)
        .enqueueUniquePeriodicWork(
            "content_sync",
            ExistingPeriodicWorkPolicy.REPLACE,
            new PeriodicWorkRequestBuilder<ContentSyncWorker>(
                Constants.SYNC_CHECK_INTERVAL_MINUTES,
                TimeUnit.MINUTES
            ).build()
        );
}
            </pre>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">API Response Examples</button>
        <div x-show="open" class="mt-2" x-transition>
            <pre class="bg-gray-100 dark:bg-gray-900 p-4 rounded text-xs overflow-x-auto">
// Authentication Response
{
    "success": true,
    "token": "device_token_here",
    "device_id": "123",
    "timestamp": "2024-03-20T10:00:00Z"
}

// Heartbeat Response
{
    "success": true,
    "timestamp": "2024-03-20T10:00:00Z",
    "needs_sync": true
}

// Sync Response
{
    "success": true,
    "device": {
        "id": "123",
        "name": "Lobby Display",
        "type": "android",
        "settings": {}
    },
    "screens": [
        {
            "screen_id": "1",
            "screen_name": "Main Screen",
            "resolution": "1920x1080",
            "orientation": "landscape",
            "settings": {},
            "contents": [
                {
                    "id": "1",
                    "name": "Welcome Message",
                    "type": "html",
                    "content_data": {},
                    "duration": 30,
                    "order": 1,
                    "settings": {},
                    "rendered_html": "<div>...</div>",
                    "media_url": "signed_url_here"
                }
            ]
        }
    ],
    "ota_update": {
        "version": "1.0.1",
        "download_url": "signed_url_here",
        "checksum": "sha256_hash",
        "release_notes": "Bug fixes and improvements"
    },
    "timestamp": "2024-03-20T10:00:00Z"
}
            </pre>
        </div>
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Device Registration Token</label>
        <div class="flex items-center space-x-2">
            <input type="text" readonly value="{{ $tenantToken }}" class="w-64 px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs">
            <button x-data="{}" @click="$clipboard('{{ $tenantToken }}')" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded">Copy</button>
        </div>
    </div>
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open" class="text-sm font-semibold text-gray-900 dark:text-gray-100 underline">Troubleshooting & FAQ</button>
        <div x-show="open" class="mt-2" x-transition>
            <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-200">
                <li>Ensure your device has internet access and correct time settings.</li>
                <li>Check API URL and tenant ID for typos.</li>
                <li>Verify the device token is correctly stored and used in API requests.</li>
                <li>Enable debug logging by setting <code>client.setDebugMode(true)</code>.</li>
                <li>Contact support at <a href="mailto:support@signagesaas.com" class="underline">support@signagesaas.com</a></li>
            </ul>
        </div>
    </div>
</div> 