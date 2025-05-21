# SignageSaaS Device Integration SDKs

This directory contains client SDKs for integrating various device platforms with the SignageSaaS platform. These SDKs implement the Device Integration Layer API to handle device authentication, content synchronization, OTA updates, and status reporting.

## Available SDKs

- [Android SDK](./android/README.md) - Java/Kotlin SDK for Android-based signage devices
- [Raspberry Pi SDK](./raspberry-pi/README.md) - Python SDK for Raspberry Pi devices
- [Windows SDK](./windows/README.md) - C# SDK for Windows-based signage players

## Core Functionality

All SDKs implement the following core functionality:

- **Device Authentication**: Secure token-based handshake mechanism
- **Content Synchronization**: API for syncing media to edge devices
- **OTA Updates**: Secure delivery of software updates via signed URLs
- **Status Reporting**: Real-time heartbeat ping system

## Integration Guide

Please refer to the README in each SDK directory for platform-specific integration instructions.
