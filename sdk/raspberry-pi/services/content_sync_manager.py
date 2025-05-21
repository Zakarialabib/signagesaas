import logging
import requests
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

logger = logging.getLogger(__name__)

class ContentSyncError(Exception):
    """Custom exception for content sync failures"""
    pass

class ContentSyncManager:
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
    
    def sync_content(self, device_id):
        """
        Sync content for the specified device
        
        Args:
            device_id (str): Device identifier
            
        Returns:
            dict: Sync response data
            
        Raises:
            ContentSyncError: If sync fails after retries
        """
        url = f"{self.base_url}/api/device/sync/{device_id}"
        headers = {
            "Authorization": f"Bearer {self.auth_token}"
        }
        
        try:
            logger.info(f"Starting content sync for device {device_id}")
            response = self.session.get(url, headers=headers)
            response.raise_for_status()
            
            data = response.json()
            if not data.get('success', False):
                raise ContentSyncError(f"Sync failed: {data.get('message', 'Unknown error')}")
                
            logger.info(f"Successfully synced content for device {device_id}")
            return data
            
        except requests.exceptions.RequestException as e:
            logger.error(f"Content sync failed for device {device_id}: {str(e)}")
            raise ContentSyncError(f"Sync request failed: {str(e)}")