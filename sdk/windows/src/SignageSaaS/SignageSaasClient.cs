using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Text.Json;
using System.Threading;
using System.Threading.Tasks;
using SignageSaaS.Models;
using SignageSaaS.Services;
using SignageSaaS.Utils;

namespace SignageSaaS
{
    /// <summary>
    /// Main client class for the SignageSaaS Windows SDK.
    /// Handles device registration, authentication, content synchronization, and status reporting.
    /// </summary>
    public class SignageSaasClient : IDisposable
    {
        private readonly string _apiUrl;
        private readonly string _tenantId;
        private readonly HttpClient _httpClient;
        private string _deviceToken;
        
        private readonly ContentSyncManager _contentSyncManager;
        private readonly HeartbeatService _heartbeatService;
        private readonly OtaUpdateManager _otaUpdateManager;
        
        /// <summary>
        /// Creates a new SignageSaasClient instance.
        /// </summary>
        /// <param name="apiUrl">The URL of the SignageSaaS API</param>
        /// <param name="tenantId">The tenant ID for the SignageSaaS platform</param>
        public SignageSaasClient(string apiUrl, string tenantId)
        {
            _apiUrl = apiUrl;
            _tenantId = tenantId;
            
            _httpClient = new HttpClient
            {
                Timeout = TimeSpan.FromSeconds(30)
            };
            
            _contentSyncManager = new ContentSyncManager(this);
            _heartbeatService = new HeartbeatService(this);
            _otaUpdateManager = new OtaUpdateManager(this);
            
            // Try to load existing token
            _deviceToken = SecureStorage.GetToken();
            
            if (!string.IsNullOrEmpty(_deviceToken))
            {
                _httpClient.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", _deviceToken);
            }
        }
        
        /// <summary>
        /// Register device with the SignageSaaS platform.
        /// </summary>
        /// <param name="deviceInfo">Information about the device</param>
        /// <returns>The device token</returns>
        public async Task<string> RegisterDeviceAsync(DeviceInfo deviceInfo)
        {
            var payload = new
            {
                tenantId = _tenantId,
                name = deviceInfo.Name,
                type = deviceInfo.Type,
                hardwareId = deviceInfo.HardwareId,
                ipAddress = deviceInfo.IpAddress,
                screenResolution = deviceInfo.ScreenResolution,
                orientation = deviceInfo.Orientation,
                osVersion = deviceInfo.OsVersion,
                appVersion = deviceInfo.AppVersion
            };
            
            var content = new StringContent(
                JsonSerializer.Serialize(payload),
                Encoding.UTF8,
                "application/json"
            );
            
            var response = await _httpClient.PostAsync($"{_apiUrl}/device/register", content);
            
            if (!response.IsSuccessStatusCode)
            {
                throw new SignageSaasException($"Registration failed: {response.StatusCode} - {await response.Content.ReadAsStringAsync()}");
            }
            
            var responseContent = await response.Content.ReadAsStringAsync();
            var responseData = JsonSerializer.Deserialize<RegistrationResponse>(responseContent);
            
            _deviceToken = responseData.Token;
            
            // Save token securely
            SecureStorage.SaveToken(_deviceToken);
            
            // Set authorization header for future requests
            _httpClient.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", _deviceToken);
            
            return _deviceToken;
        }
        
        /// <summary>
        /// Sync content from the SignageSaaS platform.
        /// </summary>
        public async Task SyncContentAsync()
        {
            if (string.IsNullOrEmpty(_deviceToken))
            {
                throw new SignageSaasException("Device not registered");
            }
            
            await _contentSyncManager.SyncContentAsync();
        }
        
        /// <summary>
        /// Start the heartbeat service to send regular status updates.
        /// </summary>
        /// <param name="intervalSeconds">The interval between heartbeats in seconds</param>
        public void StartHeartbeatService(int intervalSeconds = 30)
        {
            if (string.IsNullOrEmpty(_deviceToken))
            {
                throw new SignageSaasException("Device not registered");
            }
            
            _heartbeatService.Start(intervalSeconds);
        }
        
        /// <summary>
        /// Stop the heartbeat service.
        /// </summary>
        public void StopHeartbeatService()
        {
            _heartbeatService.Stop();
        }
        
        /// <summary>
        /// Check for and download OTA updates.
        /// </summary>
        public async Task CheckForUpdatesAsync()
        {
            if (string.IsNullOrEmpty(_deviceToken))
            {
                throw new SignageSaasException("Device not registered");
            }
            
            await _otaUpdateManager.CheckForUpdatesAsync();
        }
        
        /// <summary>
        /// Send a heartbeat to the SignageSaaS platform.
        /// </summary>
        /// <param name="status">The current status of the device</param>
        /// <param name="metrics">Performance metrics (CPU, memory, etc.)</param>
        /// <param name="screenStatus">Status of connected displays</param>
        /// <returns>True if the heartbeat was sent successfully</returns>
        public async Task<bool> SendHeartbeatAsync(string status = "online", object metrics = null, object screenStatus = null)
        {
            if (string.IsNullOrEmpty(_deviceToken))
            {
                throw new SignageSaasException("Device not registered");
            }
            
            // Get system information
            var systemInfo = new
            {
                hostname = Environment.MachineName,
                os = Environment.OSVersion.ToString(),
                processor = Environment.ProcessorCount,
                dotnet_version = Environment.Version.ToString(),
                uptime = Environment.TickCount / 1000.0
            };
            
            // Get network information
            var networkInfo = new
            {
                ip_address = System.Net.Dns.GetHostEntry(System.Net.Dns.GetHostName()).AddressList[0].ToString()
            };
            
            // Get storage information
            var storageInfo = SystemUtils.GetStorageInfo();
            
            var payload = new
            {
                status,
                ip_address = networkInfo.ip_address,
                metrics = metrics ?? new { },
                app_version = "1.0.0", // Replace with actual version
                screen_status = screenStatus ?? new { },
                storage_info = storageInfo,
                network_info = networkInfo,
                system_info = systemInfo
            };
            
            try
            {
                var content = new StringContent(
                    JsonSerializer.Serialize(payload),
                    Encoding.UTF8,
                    "application/json"
                );
                
                var response = await _httpClient.PostAsync($"{_apiUrl}/device/heartbeat", content);
                
                return response.IsSuccessStatusCode;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error sending heartbeat: {ex.Message}");
                return false;
            }
        }
        
        /// <summary>
        /// Get the API URL.
        /// </summary>
        public string ApiUrl => _apiUrl;
        
        /// <summary>
        /// Get the tenant ID.
        /// </summary>
        public string TenantId => _tenantId;
        
        /// <summary>
        /// Get the device token.
        /// </summary>
        public string DeviceToken => _deviceToken;
        
        /// <summary>
        /// Get the HTTP client.
        /// </summary>
        public HttpClient HttpClient => _httpClient;
        
        /// <summary>
        /// Dispose of resources.
        /// </summary>
        public void Dispose()
        {
            _heartbeatService.Stop();
            _httpClient.Dispose();
        }
        
        /// <summary>
        /// Response from device registration.
        /// </summary>
        private class RegistrationResponse
        {
            public string Token { get; set; }
        }
    }
    
    /// <summary>
    /// Exception thrown by the SignageSaaS client.
    /// </summary>
    public class SignageSaasException : Exception
    {
        public SignageSaasException(string message) : base(message) { }
        public SignageSaasException(string message, Exception innerException) : base(message, innerException) { }
    }
}