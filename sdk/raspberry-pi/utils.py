import logging
import uuid
import hashlib
import platform
import subprocess

logger = logging.getLogger(__name__)

def get_hardware_id():
    """
    Generate a unique hardware identifier for the device
    
    Returns:
        str: Unique hardware identifier
    """
    try:
        # Get system information to generate unique ID
        system_info = {
            'machine': platform.machine(),
            'node': platform.node(),
            'processor': platform.processor(),
            'system': platform.system()
        }
        
        # Try to get CPU serial if available
        try:
            if platform.system() == 'Linux':
                cpu_info = subprocess.check_output(['cat', '/proc/cpuinfo']).decode()
                serial_line = [line for line in cpu_info.split('\n') if 'Serial' in line]
                if serial_line:
                    system_info['cpu_serial'] = serial_line[0].split(':')[1].strip()
        except Exception:
            pass
        
        # Create hash from system info
        hardware_hash = hashlib.sha256(str(system_info).encode()).hexdigest()
        return f"{hardware_hash[:8]}-{hardware_hash[8:12]}-{hardware_hash[12:16]}-{hardware_hash[16:20]}-{hardware_hash[20:32]}"
        
    except Exception as e:
        logger.warning(f"Could not generate hardware ID: {str(e)}")
        # Fallback to random UUID if hardware ID generation fails
        return str(uuid.uuid4())

def validate_response(response):
    """
    Validate API response structure
    
    Args:
        response (dict): API response to validate
        
    Returns:
        bool: True if response is valid
    """
    return isinstance(response, dict) and response.get('success', False)

def exponential_backoff(fn, max_retries=5, initial_delay=5):
    """
    Decorator for exponential backoff retry logic
    
    Args:
        fn: Function to decorate
        max_retries: Maximum number of retries
        initial_delay: Initial delay in seconds
    """
    def wrapper(*args, **kwargs):
        retries = 0
        delay = initial_delay
        
        while retries < max_retries:
            try:
                return fn(*args, **kwargs)
            except Exception as e:
                retries += 1
                if retries >= max_retries:
                    raise
                
                logger.warning(f"Attempt {retries} failed: {str(e)}. Retrying in {delay} seconds...")
                time.sleep(delay)
                delay *= 2
                
    return wrapper