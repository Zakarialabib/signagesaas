import logging
import json
import os
from cryptography.fernet import Fernet

logger = logging.getLogger(__name__)

class SecureStorageError(Exception):
    """Custom exception for secure storage failures"""
    pass

class SecureStorage:
    def __init__(self, storage_path, encryption_key=None):
        """
        Initialize secure storage
        
        Args:
            storage_path (str): Path to storage file
            encryption_key (bytes, optional): Encryption key. If None, will generate new key.
        """
        self.storage_path = storage_path
        
        if encryption_key is None:
            self.encryption_key = Fernet.generate_key()
        else:
            self.encryption_key = encryption_key
            
        self.cipher = Fernet(self.encryption_key)
    
    def save_credentials(self, credentials):
        """
        Save credentials securely
        
        Args:
            credentials (dict): Credentials to store
            
        Raises:
            SecureStorageError: If save fails
        """
        try:
            logger.info(f"Saving credentials to {self.storage_path}")
            encrypted = self.cipher.encrypt(json.dumps(credentials).encode())
            
            with open(self.storage_path, 'wb') as f:
                f.write(encrypted)
                
            logger.info("Credentials saved successfully")
            
        except (IOError, json.JSONDecodeError) as e:
            logger.error(f"Failed to save credentials: {str(e)}")
            raise SecureStorageError(f"Save failed: {str(e)}")
    
    def load_credentials(self):
        """
        Load stored credentials
        
        Returns:
            dict: Decrypted credentials
            
        Raises:
            SecureStorageError: If load fails
        """
        try:
            if not os.path.exists(self.storage_path):
                return None
                
            logger.info(f"Loading credentials from {self.storage_path}")
            
            with open(self.storage_path, 'rb') as f:
                encrypted = f.read()
                
            decrypted = self.cipher.decrypt(encrypted).decode()
            credentials = json.loads(decrypted)
            
            logger.info("Credentials loaded successfully")
            return credentials
            
        except (IOError, json.JSONDecodeError) as e:
            logger.error(f"Failed to load credentials: {str(e)}")
            raise SecureStorageError(f"Load failed: {str(e)}")