import logging
import requests
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

logger = logging.getLogger(__name__)

class HeartbeatError(Exception):
    """Custom exception for heartbeat failures"""
    pass

class HeartbeatService:
    def __init__(self, base_url, auth_token):
        self.base_url = base_url
        self.auth_token = auth_token
        
        # Configure retry strategy
        self.session = requests.Session()
        retries = Retry(
            total=3,
            backoff_factor=1,
            status_forcelist=[500, 502, 503, 504]
        )
        self.session.mount('https://', HTTPAdapter(max_retries=retries))
    
    def send_heartbeat(self, device_id, status_data):
        """
        Send heartbeat status for the specified device
        
        Args:
            device_id (str): Device identifier
            status_data (dict): Device status metrics
            
        Returns:
            dict: Heartbeat response data
            
        Raises:
            HeartbeatError: If heartbeat fails after retries
        """
        url = f"{self.base_url}/api/device/heartbeat/{device_id}"
        headers = {
            "Authorization": f"Bearer {self.auth_token}"
        }
        
        try:
            logger.info(f"Sending heartbeat for device {device_id}")
            response = self.session.post(url, json=status_data, headers=headers)
            response.raise_for_status()
            
            data = response.json()
            if not data.get('success', False):
                raise HeartbeatError(f"Heartbeat failed: {data.get('message', 'Unknown error')}")
                
            logger.info(f"Successfully sent heartbeat for device {device_id}")
            return data
            
        except requests.exceptions.RequestException as e:
            logger.error(f"Heartbeat failed for device {device_id}: {str(e)}")
            raise HeartbeatError(f"Heartbeat request failed: {str(e)}")