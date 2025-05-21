import logging
import os
import uuid

class UtilsException(Exception): pass

class SecureStorage:
    """
    Handles secure storage of device tokens (simple file-based for now).
    """
    TOKEN_FILE = 'device_token.txt'

    def __init__(self):
        self.logger = logging.getLogger('SecureStorage')

    def save_token(self, token):
        try:
            with open(self.TOKEN_FILE, 'w') as f:
                f.write(token)
            self.logger.info('Device token saved securely.')
        except Exception as e:
            self.logger.error(f'Failed to save token: {e}')
            raise UtilsException(str(e))

    def get_token(self):
        try:
            if not os.path.exists(self.TOKEN_FILE):
                return None
            with open(self.TOKEN_FILE, 'r') as f:
                token = f.read().strip()
            self.logger.info('Device token loaded from secure storage.')
            return token
        except Exception as e:
            self.logger.error(f'Failed to load token: {e}')
            raise UtilsException(str(e))


def get_hardware_id():
    """
    Returns a unique hardware identifier for the device (stubbed for now).
    """
    try:
        # TODO: Implement platform-specific hardware ID retrieval
        return str(uuid.uuid4())
    except Exception as e:
        logging.getLogger('utils').error(f'Failed to get hardware ID: {e}')
        raise UtilsException(str(e)) 