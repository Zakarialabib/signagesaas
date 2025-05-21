import json
import os
import platform
import socket
import time
import uuid
from datetime import datetime
from threading import Thread

import requests

from .models import DeviceInfo
from .services import ContentSyncManager, HeartbeatService, OtaUpdateManager
from .utils import get_hardware_id, SecureStorage

class SignageSaasClient:
    """
    Main client class for the SignageSaaS Raspberry Pi SDK.
    Handles device registration, authentication, content synchronization, and status reporting.
    """
    
    def __init__(self, api_url, tenant_id):
        """
        Initialize the SignageSaaS client.
        
        Args:
            api_url (str): The URL of the SignageSaaS API
            tenant_id (str): The tenant ID for the SignageSaaS platform
        """
        self.api_url = api_url
        self.tenant_id = tenant_id
        self.device_token = None
        
        # Initialize services
        self.storage = SecureStorage()
        self.content_sync = ContentSyncManager(self)
        self.heartbeat = HeartbeatService(self)
        self.ota_update = OtaUpdateManager(self)
        
        # Try to load existing token
        self.device_token = self.storage.get_token()
        
        # Utility functions
        self.utils = type('Utils', (), {
            'get_hardware_id': get_hardware_id
        })
    
    def register_device(self, device_info):
        """
        Register the device with the SignageSaaS platform.
        
        Args:
            device_info (DeviceInfo): Information about the device
            
        Returns:
            str: The device token
            
        Raises:
            Exception: If registration fails
        """
        payload = {
            'tenantId': self.tenant_id,
            'name': device_info.name,
            'type': device_info.type,
            'hardwareId': device_info.hardware_id,
            'ipAddress': device_info.ip_address,
            'screenResolution': device_info.screen_resolution,
            'orientation': device_info.orientation,
            'osVersion': device_info.os_version,
            'appVersion': device_info.app_version
        }
        
        response = requests.post(
            f"{self.api_url}/device/register",
            json=payload
        )
        
        if response.status_code != 200:
            raise Exception(f"Registration failed: {response.status_code} - {response.text}")
        
        data = response.json()
        self.device_token = data.get('token')
        
        # Save token securely
        self.storage.save_token(self.device_token)
        
        return self.device_token
    
    def sync_content(self):
        """
        Sync content from the SignageSaaS platform.
        
        Raises:
            Exception: If the device is not registered
        """
        if not self.device_token:
            raise Exception("Device not registered")
        
        return self.content_sync.sync_content()
    
    def start_heartbeat_service(self, interval_seconds=30):
        """
        Start the heartbeat service to send regular status updates.
        
        Args:
            interval_seconds (int): The interval between heartbeats in seconds
            
        Raises:
            Exception: If the device is not registered
        """
        if not self.device_token:
            raise Exception("Device not registered")
        
        self.heartbeat.start(interval_seconds)
    
    def stop_heartbeat_service(self):
        """
        Stop the heartbeat service.
        """
        self.heartbeat.stop()
    
    def check_for_updates(self):
        """
        Check for and download OTA updates.
        
        Raises:
            Exception: If the device is not registered
        """
        if not self.device_token:
            raise Exception("Device not registered")
        
        return self.ota_update.check_for_updates()
    
    def send_heartbeat(self, status="online", metrics=None, screen_status=None):
        """
        Send a heartbeat to the SignageSaaS platform.
        
        Args:
            status (str): The current status of the device
            metrics (dict): Performance metrics (CPU, memory, temperature)
            screen_status (dict): Status of connected displays
            
        Returns:
            bool: True if the heartbeat was sent successfully
            
        Raises:
            Exception: If the device is not registered
        """
        if not self.device_token:
            raise Exception("Device not registered")
        
        # Get system information
        system_info = {
            'hostname': socket.gethostname(),
            'platform': platform.platform(),
            'processor': platform.processor(),
            'python_version': platform.python_version(),
            'uptime': self._get_uptime()
        }
        
        # Get network information
        network_info = {
            'ip_address': socket.gethostbyname(socket.gethostname())
        }
        
        # Get storage information
        storage_info = self._get_storage_info()
        
        payload = {
            'status': status,
            'ip_address': network_info['ip_address'],
            'metrics': metrics or {},
            'app_version': '1.0.0',  # Replace with actual version
            'screen_status': screen_status or {},
            'storage_info': storage_info,
            'network_info': network_info,
            'system_info': system_info
        }
        
        headers = {
            'Authorization': f'Bearer {self.device_token}'
        }
        
        try:
            response = requests.post(
                f"{self.api_url}/device/heartbeat",
                json=payload,
                headers=headers
            )
            
            return response.status_code == 200
        except Exception as e:
            print(f"Error sending heartbeat: {e}")
            return False
    
    def _get_uptime(self):
        """
        Get the system uptime.
        
        Returns:
            float: The system uptime in seconds
        """
        try:
            with open('/proc/uptime', 'r') as f:
                uptime_seconds = float(f.readline().split()[0])
                return uptime_seconds
        except:
            return 0
    
    def _get_storage_info(self):
        """
        Get storage information.
        
        Returns:
            dict: Storage information
        """
        try:
            statvfs = os.statvfs('/')
            total = statvfs.f_frsize * statvfs.f_blocks
            free = statvfs.f_frsize * statvfs.f_bfree
            used = total - free
            
            return {
                'total': total,
                'used': used,
                'free': free,
                'percent_used': (used / total) * 100
            }
        except:
            return {}