using System;

namespace SignageSaaS.Models
{
    /// <summary>
    /// Class for storing device information used during registration.
    /// </summary>
    public class DeviceInfo
    {
        /// <summary>
        /// Gets or sets the name of the device.
        /// </summary>
        public string Name { get; set; }
        
        /// <summary>
        /// Gets or sets the type of device (e.g., "windows").
        /// </summary>
        public string Type { get; set; }
        
        /// <summary>
        /// Gets or sets a unique identifier for the hardware.
        /// </summary>
        public string HardwareId { get; set; }
        
        /// <summary>
        /// Gets or sets the current IP address of the device.
        /// </summary>
        public string IpAddress { get; set; }
        
        /// <summary>
        /// Gets or sets the screen resolution (e.g., "1920x1080").
        /// </summary>
        public string ScreenResolution { get; set; }
        
        /// <summary>
        /// Gets or sets the screen orientation ("landscape" or "portrait").
        /// </summary>
        public string Orientation { get; set; } = "landscape";
        
        /// <summary>
        /// Gets or sets the operating system version.
        /// </summary>
        public string OsVersion { get; set; }
        
        /// <summary>
        /// Gets or sets the application version.
        /// </summary>
        public string AppVersion { get; set; }
        
        /// <summary>
        /// Gets or sets the physical location of the device.
        /// </summary>
        public object Location { get; set; }
        
        /// <summary>
        /// Gets or sets the timezone of the device.
        /// </summary>
        public string Timezone { get; set; }
        
        /// <summary>
        /// Validates that the required properties are set.
        /// </summary>
        /// <exception cref="InvalidOperationException">Thrown when a required property is not set.</exception>
        public void Validate()
        {
            if (string.IsNullOrEmpty(Name))
            {
                throw new InvalidOperationException("Device name is required");
            }
            
            if (string.IsNullOrEmpty(Type))
            {
                throw new InvalidOperationException("Device type is required");
            }
            
            if (string.IsNullOrEmpty(HardwareId))
            {
                throw new InvalidOperationException("Hardware ID is required");
            }
        }
    }
}