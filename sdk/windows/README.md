# SignageSaaS Windows SDK (C#)

A C# SDK for integrating Windows-based digital signage devices with the SignageSaaS platform. Handles device authentication, media syncing, OTA updates, and heartbeat reporting.

---

## Features

- Device registration and authentication
- Secure token storage
- Media/content synchronization
- OTA (over-the-air) update checks
- Heartbeat/status reporting
- Robust logging and error handling
- Async/await for all network calls

---

## Requirements

- .NET 6.0+
- `Microsoft.Extensions.Logging` (for logging)
- `Polly` (recommended for retry logic)

---

## Installation

Add the SDK source files to your project, or package as a NuGet library (coming soon).

---

## Usage Example

```csharp
using SignageSaaS;
using SignageSaaS.Models;

// Initialize client
var client = new SignageSaasClient(apiUrl: "https://api.your-signagesaas.com", tenantId: "your-tenant-id");

// Register device
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

// Sync content
await client.SyncContentAsync();

// Start heartbeat
client.StartHeartbeatService(intervalSeconds: 30);

// Check for OTA updates
await client.CheckForUpdatesAsync();

// Stop heartbeat
client.StopHeartbeatService();
```

---

## API Endpoints Used

- `POST /device/register` — Register/authenticate device
- `POST /device/heartbeat` — Send heartbeat/status
- `GET /content/sync` — Sync content (to be implemented)
- `GET /device/ota` — Check for OTA updates (to be implemented)

---

## Logging & Error Handling

- Uses .NET logging (`ILogger` or fallback to console logger)
- Custom exceptions for registration, sync, OTA, and heartbeat errors
- All network calls are async/await
- Add Polly for retry/backoff logic (recommended)

---

## Extending & Testing

- Implement the TODOs in `Services` and `Utils` for full functionality
- Add unit/integration tests for all flows

---

## License

MIT
