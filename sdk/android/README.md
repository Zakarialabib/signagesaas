# SignageSaaS Android SDK

## Overview

The SignageSaaS Android SDK provides a simple way to integrate Android-based digital signage devices with the SignageSaaS platform. This SDK handles device registration, authentication, content synchronization, OTA updates, and status reporting.

## Features

- **Device Registration & Authentication**: Securely register and authenticate devices with the SignageSaaS platform
- **Content Synchronization**: Automatically sync and cache media content
- **OTA Updates**: Receive and apply software updates
- **Status Reporting**: Send regular heartbeats with device status and diagnostics

## Installation

### Gradle

Add the following to your project's build.gradle file:

```gradle
dependencies {
    implementation 'com.signagesaas:android-sdk:1.0.0'
}
```

## Quick Start

```java
// Initialize the SDK
SignageSaasClient client = new SignageSaasClient.Builder()
    .setApiUrl("https://your-signagesaas-instance.com/api")
    .setTenantId("your-tenant-id")
    .build();

// Register device
DeviceInfo deviceInfo = new DeviceInfo.Builder()
    .setName("Android Player 1")
    .setType("android")
    .setHardwareId(DeviceUtils.getUniqueHardwareId())
    .setIpAddress(NetworkUtils.getIpAddress())
    .setScreenResolution(ScreenUtils.getResolution())
    .setOrientation(ScreenUtils.getOrientation())
    .setOsVersion(Build.VERSION.RELEASE)
    .setAppVersion(BuildConfig.VERSION_NAME)
    .build();

client.registerDevice(deviceInfo, new RegisterCallback() {
    @Override
    public void onSuccess(String deviceToken) {
        // Store token securely
        SecureStorage.saveToken(deviceToken);

        // Start content sync
        client.syncContent();

        // Start heartbeat service
        client.startHeartbeatService(30); // 30 second interval
    }

    @Override
    public void onError(SignageSaasException e) {
        Log.e("SignageSaaS", "Registration failed", e);
    }
});
```

## API Reference

### SignageSaasClient

Main client class for interacting with the SignageSaaS platform.

### DeviceInfo

Class for storing device information used during registration.

### HeartbeatService

Service for sending regular status updates to the platform.

### ContentSyncManager

Handles downloading and caching of media content.

### OtaUpdateManager

Handles downloading and applying OTA updates.

## License

This SDK is distributed under the MIT license.
