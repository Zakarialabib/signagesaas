class DeviceInfo:
    """
    Class for storing device information used during registration.
    """
    
    def __init__(self, name, type, hardware_id, ip_address=None, screen_resolution=None,
                 orientation="landscape", os_version=None, app_version=None, location=None, timezone=None):
        """
        Initialize device information.
        
        Args:
            name (str): The name of the device
            type (str): The type of device (e.g., "raspberry-pi")
            hardware_id (str): A unique identifier for the hardware
            ip_address (str, optional): The current IP address of the device
            screen_resolution (str, optional): The screen resolution (e.g., "1920x1080")
            orientation (str, optional): The screen orientation ("landscape" or "portrait")
            os_version (str, optional): The operating system version
            app_version (str, optional): The application version
            location (dict, optional): The physical location of the device
            timezone (str, optional): The timezone of the device
        """
        self.name = name
        self.type = type
        self.hardware_id = hardware_id
        self.ip_address = ip_address
        self.screen_resolution = screen_resolution
        self.orientation = orientation
        self.os_version = os_version
        self.app_version = app_version
        self.location = location
        self.timezone = timezone
        
    def to_dict(self):
        """
        Convert the device information to a dictionary.
        
        Returns:
            dict: The device information as a dictionary
        """
        return {
            'name': self.name,
            'type': self.type,
            'hardware_id': self.hardware_id,
            'ip_address': self.ip_address,
            'screen_resolution': self.screen_resolution,
            'orientation': self.orientation,
            'os_version': self.os_version,
            'app_version': self.app_version,
            'location': self.location,
            'timezone': self.timezone
        }