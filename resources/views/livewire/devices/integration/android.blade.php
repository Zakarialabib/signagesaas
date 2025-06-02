<div>
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Android Device Integration Guide</h2>
    
    <!-- Quick Start Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quick Start</h3>
        <div class="mt-2 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Follow these steps to quickly integrate your Android device:
            </p>
            <ol class="list-decimal pl-5 mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li>Add required dependencies to your <code>build.gradle</code></li>
                <li>Initialize the SDK with your tenant token</li>
                <li>Implement the device registration flow</li>
                <li>Set up background services for heartbeat and content sync</li>
            </ol>
        </div>
    </div>

    <!-- API Reference Section -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">API Reference</h3>
        <div class="mt-2 space-y-4">
            <!-- Authentication -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Authentication</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Authenticate your device to receive an access token for API calls.
                </p>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded mt-2 overflow-x-auto">
POST /api/device/authenticate
Content-Type: application/json

{
    "hardware_id": "unique-device-identifier",
    "tenant_id": "{{ $tenantToken }}"
}

Response:
{
    "success": true,
    "token": "device-auth-token",
    "device_id": "device-uuid",
    "timestamp": "2024-03-20T10:00:00Z"
}</pre>
            </div>

            <!-- Heartbeat -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Device Heartbeat</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Send periodic heartbeat to report device status and metrics.
                </p>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded mt-2 overflow-x-auto">
POST /api/device/heartbeat/{device}
Authorization: Bearer {device-token}
Content-Type: application/json

{
    "status": "online",
    "ip_address": "192.168.1.100",
    "metrics": {
        "cpu_usage": 23.5,
        "memory_usage": 512.6,
        "uptime": 86400
    },
    "app_version": "1.0.0",
    "screen_status": {
        "power": "on",
        "brightness": 80
    },
    "storage_info": {
        "total": 32000000000,
        "free": 16000000000
    },
    "network_info": {
        "type": "wifi",
        "signal_strength": 85
    },
    "system_info": {
        "os_version": "Android 11",
        "model": "SM-A515F"
    }
}</pre>
            </div>

            <!-- Content Sync -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Content Sync</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Sync content and receive display layouts.
                </p>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded mt-2 overflow-x-auto">
GET /api/device/content/sync/{device}
Authorization: Bearer {device-token}

Response:
{
    "success": true,
    "device": {
        "id": "device-uuid",
        "name": "Lobby Display",
        "type": "android",
        "settings": {
            "volume": 70,
            "brightness": 80
        }
    },
    "screens": [
        {
            "screen_id": "screen-uuid",
            "name": "Main Screen",
            "resolution": "1920x1080",
            "orientation": "landscape",
            "contents": [
                {
                    "id": "content-uuid",
                    "type": "html",
                    "content_data": {},
                    "duration": 30,
                    "order": 1,
                    "media_url": "signed-url-here"
                }
            ]
        }
    ],
    "timestamp": "2024-03-20T10:00:00Z"
}</pre>
            </div>

            <!-- OTA Updates -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">OTA Updates</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Check and download app updates.
                </p>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded mt-2 overflow-x-auto">
GET /api/device/update/check/{device}
Authorization: Bearer {device-token}

Response:
{
    "success": true,
    "has_update": true,
    "update": {
        "version": "1.1.0",
        "download_url": "signed-url-here",
        "size": 15000000,
        "checksum": "sha256-hash",
        "release_notes": "Bug fixes and improvements"
    }
}</pre>
            </div>
        </div>
    </div>

    <!-- Implementation Guide -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Implementation Guide</h3>
        <div class="mt-2 space-y-4">
            <!-- Dependencies -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Required Dependencies</h4>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-x-auto">
// build.gradle (Module: app)
dependencies {
    // Network
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:okhttp:4.9.1'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.9.1'
    
    // Background Tasks
    implementation 'androidx.work:work-runtime:2.8.1'
    
    // Security
    implementation 'androidx.security:security-crypto:1.1.0-alpha06'
    
    // Media
    implementation 'com.google.android.exoplayer:exoplayer:2.19.1'
    
    // Image Loading
    implementation 'com.github.bumptech.glide:glide:4.15.1'
}</pre>
            </div>

            <!-- SDK Initialization -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">SDK Initialization</h4>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-x-auto">
// Application.kt
class SignageApplication : Application() {
    override fun onCreate() {
        super.onCreate()
        
        SignageSDK.initialize(
            context = this,
            tenantToken = "{{ $tenantToken }}",
            config = SignageConfig(
                heartbeatInterval = 5.minutes,
                contentSyncInterval = 15.minutes,
                maxRetryAttempts = 3,
                logLevel = BuildConfig.DEBUG ? LogLevel.DEBUG : LogLevel.ERROR
            )
        )
    }
}</pre>
            </div>

            <!-- Background Services -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Background Services</h4>
                <pre class="text-sm bg-gray-100 dark:bg-gray-900 p-4 rounded overflow-x-auto">
// HeartbeatWorker.kt
class HeartbeatWorker(
    context: Context,
    params: WorkerParameters
) : CoroutineWorker(context, params) {

    override suspend fun doWork(): Result {
        return try {
            val deviceManager = DeviceManager.getInstance()
            deviceManager.sendHeartbeat()
            Result.success()
        } catch (e: Exception) {
            if (runAttemptCount < 3) {
                Result.retry()
            } else {
                Result.failure()
            }
        }
    }
}

// ContentSyncWorker.kt
class ContentSyncWorker(
    context: Context,
    params: WorkerParameters
) : CoroutineWorker(context, params) {

    override suspend fun doWork(): Result {
        return try {
            val contentManager = ContentManager.getInstance()
            contentManager.syncContent()
            Result.success()
        } catch (e: Exception) {
            if (runAttemptCount < 3) {
                Result.retry()
            } else {
                Result.failure()
            }
        }
    }
}</pre>
            </div>
        </div>
    </div>

    <!-- Best Practices -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Best Practices</h3>
        <div class="mt-2 space-y-4">
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Device Management</h4>
                <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-200 space-y-2">
                    <li>Implement proper error handling with exponential backoff</li>
                    <li>Use WorkManager for reliable background tasks</li>
                    <li>Store sensitive data using EncryptedSharedPreferences</li>
                    <li>Handle network connectivity changes gracefully</li>
                    <li>Implement proper logging for debugging</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Content Management</h4>
                <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-200 space-y-2">
                    <li>Cache media content for offline playback</li>
                    <li>Verify content integrity after download</li>
                    <li>Implement proper content rotation</li>
                    <li>Handle content transitions smoothly</li>
                    <li>Monitor content delivery status</li>
                </ul>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Performance</h4>
                <ul class="list-disc pl-5 text-sm text-gray-700 dark:text-gray-200 space-y-2">
                    <li>Optimize image and video loading</li>
                    <li>Implement proper memory management</li>
                    <li>Monitor device metrics and report issues</li>
                    <li>Handle low memory conditions</li>
                    <li>Implement proper battery optimization</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Troubleshooting</h3>
        <div class="mt-2 space-y-4">
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h4 class="font-medium text-gray-800 dark:text-gray-200">Common Issues</h4>
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-700 dark:text-gray-300">Authentication Failed</h5>
                        <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400 mt-2">
                            <li>Verify tenant token is correct</li>
                            <li>Check device registration status</li>
                            <li>Ensure device has internet access</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-700 dark:text-gray-300">Content Not Syncing</h5>
                        <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400 mt-2">
                            <li>Check network connectivity</li>
                            <li>Verify device token is valid</li>
                            <li>Check content sync logs</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-700 dark:text-gray-300">Performance Issues</h5>
                        <ul class="list-disc pl-5 text-sm text-gray-600 dark:text-gray-400 mt-2">
                            <li>Monitor device metrics</li>
                            <li>Check memory usage</li>
                            <li>Verify content optimization</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Support -->
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Support</h3>
        <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Need help? Contact our support team:
            </p>
            <ul class="mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li>Email: <a href="mailto:support@signagesaas.com" class="text-indigo-600 dark:text-indigo-400">support@signagesaas.com</a></li>
                <li>Documentation: <a href="https://docs.signagesaas.com" class="text-indigo-600 dark:text-indigo-400">docs.signagesaas.com</a></li>
                <li>GitHub: <a href="https://github.com/signagesaas/android-sdk" class="text-indigo-600 dark:text-indigo-400">github.com/signagesaas/android-sdk</a></li>
            </ul>
        </div>
    </div>

    <!-- Device Registration -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Device Registration Token</label>
        <div class="flex items-center space-x-2">
            <input type="text" readonly value="{{ $tenantToken }}" class="w-64 px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs">
            <button x-data="{}" @click="$clipboard('{{ $tenantToken }}')" class="text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Copy</button>
        </div>
    </div>
</div>