# SignageSaaS Raspberry Pi SDK (Python)

## Overview

The SignageSaaS Raspberry Pi SDK is a Python library that enables Raspberry Pi devices to integrate with the SignageSaaS platform. This SDK provides a simple interface for device registration, content synchronization, OTA updates, and status reporting.

## Features

- **Device Registration & Authentication**: Securely register and authenticate Raspberry Pi devices
- **Content Synchronization**: Download and cache media content
- **OTA Updates**: Receive and apply software updates
- **Status Reporting**: Send regular heartbeats with device status and diagnostics

## Installation

```bash
pip install signagesaas-rpi-sdk
```

## Quick Start

```python
from signagesaas import SignageSaasClient, DeviceInfo
import platform
import socket
import os

# Initialize the client
client = SignageSaasClient(
    api_url="https://your-signagesaas-instance.com/api",
    tenant_id="your-tenant-id"
)

# Prepare device information
device_info = DeviceInfo(
    name="Raspberry Pi Player 1",
    type="raspberry-pi",
    hardware_id=client.utils.get_hardware_id(),
    ip_address=socket.gethostbyname(socket.gethostname()),
    screen_resolution="1920x1080",
    orientation="landscape",
    os_version=platform.platform(),
    app_version="1.0.0"
)

# Register device
try:
    device_token = client.register_device(device_info)

    # Save token securely
    client.storage.save_token(device_token)

    # Start content sync
    client.sync_content()

    # Start heartbeat service
    client.start_heartbeat_service(interval_seconds=30)

    # Check for OTA updates
    client.check_for_updates()

except Exception as e:
    print(f"Error during registration: {e}")
```

## API Reference

### SignageSaasClient

Main client class for interacting with the SignageSaaS platform.

```python
client = SignageSaasClient(api_url, tenant_id)
```

### DeviceInfo

Class for storing device information used during registration.

### HeartbeatService

Service for sending regular status updates to the platform.

### ContentSyncManager

Handles downloading and caching of media content.

### OtaUpdateManager

Handles downloading and applying OTA updates.

## Sample Implementation

The SDK includes a sample implementation that can be used as a starting point for your Raspberry Pi digital signage application.

```bash
# Run the sample player
python -m signagesaas.samples.player --tenant-id=your-tenant-id
```

## License

This SDK is distributed under the MIT license.
