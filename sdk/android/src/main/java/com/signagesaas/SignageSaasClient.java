package com.signagesaas;

import android.content.Context;
import android.os.Build;
import android.util.Log;

import com.signagesaas.models.DeviceInfo;
import com.signagesaas.services.ContentSyncManager;
import com.signagesaas.services.HeartbeatService;
import com.signagesaas.services.OtaUpdateManager;
import com.signagesaas.utils.DeviceUtils;
import com.signagesaas.utils.NetworkUtils;
import com.signagesaas.utils.SecureStorage;

import org.json.JSONObject;

import java.io.IOException;
import java.util.concurrent.TimeUnit;

import okhttp3.MediaType;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

/**
 * Main client class for the SignageSaaS Android SDK.
 * Handles device registration, authentication, content synchronization, and status reporting.
 */
public class SignageSaasClient {
    private static final String TAG = "SignageSaasClient";
    private static final MediaType JSON = MediaType.parse("application/json; charset=utf-8");
    
    private final String apiUrl;
    private final String tenantId;
    private final Context context;
    private final OkHttpClient httpClient;
    
    private String deviceToken;
    private ContentSyncManager contentSyncManager;
    private HeartbeatService heartbeatService;
    private OtaUpdateManager otaUpdateManager;
    
    /**
     * Creates a new SignageSaasClient instance.
     */
    private SignageSaasClient(Builder builder) {
        this.apiUrl = builder.apiUrl;
        this.tenantId = builder.tenantId;
        this.context = builder.context;
        
        this.httpClient = new OkHttpClient.Builder()
                .connectTimeout(30, TimeUnit.SECONDS)
                .readTimeout(30, TimeUnit.SECONDS)
                .writeTimeout(30, TimeUnit.SECONDS)
                .build();
        
        this.contentSyncManager = new ContentSyncManager(this, context);
        this.heartbeatService = new HeartbeatService(this, context);
        this.otaUpdateManager = new OtaUpdateManager(this, context);
        
        // Try to load existing token
        this.deviceToken = SecureStorage.getToken(context);
    }
    
    /**
     * Register device with the SignageSaaS platform.
     */
    public void registerDevice(DeviceInfo deviceInfo, RegisterCallback callback) {
        try {
            JSONObject json = new JSONObject();
            json.put("tenantId", tenantId);
            json.put("name", deviceInfo.getName());
            json.put("type", deviceInfo.getType());
            json.put("hardwareId", deviceInfo.getHardwareId());
            json.put("ipAddress", deviceInfo.getIpAddress());
            json.put("screenResolution", deviceInfo.getScreenResolution());
            json.put("orientation", deviceInfo.getOrientation());
            json.put("osVersion", deviceInfo.getOsVersion());
            json.put("appVersion", deviceInfo.getAppVersion());
            
            RequestBody body = RequestBody.create(json.toString(), JSON);
            Request request = new Request.Builder()
                    .url(apiUrl + "/device/register")
                    .post(body)
                    .build();
            
            httpClient.newCall(request).enqueue(new okhttp3.Callback() {
                @Override
                public void onFailure(okhttp3.Call call, IOException e) {
                    callback.onError(new SignageSaasException("Registration failed: " + e.getMessage()));
                }
                
                @Override
                public void onResponse(okhttp3.Call call, Response response) throws IOException {
                    if (!response.isSuccessful()) {
                        callback.onError(new SignageSaasException("Registration failed: " + response.code()));
                        return;
                    }
                    
                    try {
                        String responseBody = response.body().string();
                        JSONObject jsonResponse = new JSONObject(responseBody);
                        deviceToken = jsonResponse.getString("token");
                        
                        // Save token securely
                        SecureStorage.saveToken(context, deviceToken);
                        
                        callback.onSuccess(deviceToken);
                    } catch (Exception e) {
                        callback.onError(new SignageSaasException("Failed to parse response: " + e.getMessage()));
                    }
                }
            });
        } catch (Exception e) {
            callback.onError(new SignageSaasException("Failed to create request: " + e.getMessage()));
        }
    }
    
    /**
     * Sync content from the SignageSaaS platform.
     */
    public void syncContent() {
        if (deviceToken == null) {
            Log.e(TAG, "Cannot sync content: Device not registered");
            return;
        }
        
        contentSyncManager.syncContent();
    }
    
    /**
     * Start the heartbeat service to send regular status updates.
     */
    public void startHeartbeatService(int intervalSeconds) {
        if (deviceToken == null) {
            Log.e(TAG, "Cannot start heartbeat: Device not registered");
            return;
        }
        
        heartbeatService.start(intervalSeconds);
    }
    
    /**
     * Stop the heartbeat service.
     */
    public void stopHeartbeatService() {
        heartbeatService.stop();
    }
    
    /**
     * Check for and download OTA updates.
     */
    public void checkForUpdates() {
        if (deviceToken == null) {
            Log.e(TAG, "Cannot check for updates: Device not registered");
            return;
        }
        
        otaUpdateManager.checkForUpdates();
    }
    
    /**
     * Get the API URL.
     */
    public String getApiUrl() {
        return apiUrl;
    }
    
    /**
     * Get the tenant ID.
     */
    public String getTenantId() {
        return tenantId;
    }
    
    /**
     * Get the device token.
     */
    public String getDeviceToken() {
        return deviceToken;
    }
    
    /**
     * Get the HTTP client.
     */
    public OkHttpClient getHttpClient() {
        return httpClient;
    }
    
    /**
     * Builder for creating SignageSaasClient instances.
     */
    public static class Builder {
        private String apiUrl;
        private String tenantId;
        private Context context;
        
        public Builder setApiUrl(String apiUrl) {
            this.apiUrl = apiUrl;
            return this;
        }
        
        public Builder setTenantId(String tenantId) {
            this.tenantId = tenantId;
            return this;
        }
        
        public Builder setContext(Context context) {
            this.context = context;
            return this;
        }
        
        public SignageSaasClient build() {
            if (apiUrl == null) {
                throw new IllegalStateException("API URL is required");
            }
            
            if (tenantId == null) {
                throw new IllegalStateException("Tenant ID is required");
            }
            
            if (context == null) {
                throw new IllegalStateException("Context is required");
            }
            
            return new SignageSaasClient(this);
        }
    }
    
    /**
     * Callback interface for device registration.
     */
    public interface RegisterCallback {
        void onSuccess(String deviceToken);
        void onError(SignageSaasException e);
    }
}