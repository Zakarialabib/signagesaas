import logging
import requests
import hashlib
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

logger = logging.getLogger(__name__)

class OtaUpdateError(Exception):
    """Custom exception for OTA update failures"""
    pass

class OtaUpdateManager:
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
    
    def download_update(self, download_url, destination_path, expected_checksum):
        """
        Download and verify an OTA update package
        
        Args:
            download_url (str): Signed URL for update package
            destination_path (str): Local path to save the update
            expected_checksum (str): Expected SHA256 checksum of the update
            
        Raises:
            OtaUpdateError: If download or verification fails
        """
        try:
            logger.info(f"Downloading OTA update from {download_url}")
            
            # Download with retries
            response = self.session.get(download_url, stream=True)
            response.raise_for_status()
            
            # Verify checksum
            sha256 = hashlib.sha256()
            with open(destination_path, 'wb') as f:
                for chunk in response.iter_content(chunk_size=8192):
                    sha256.update(chunk)
                    f.write(chunk)
            
            actual_checksum = sha256.hexdigest()
            if actual_checksum != expected_checksum:
                raise OtaUpdateError(f"Checksum mismatch: expected {expected_checksum}, got {actual_checksum}")
                
            logger.info(f"Successfully downloaded and verified OTA update to {destination_path}")
            
        except requests.exceptions.RequestException as e:
            logger.error(f"OTA download failed: {str(e)}")
            raise OtaUpdateError(f"Download failed: {str(e)}")
        except IOError as e:
            logger.error(f"OTA file operation failed: {str(e)}")
            raise OtaUpdateError(f"File operation failed: {str(e)}")