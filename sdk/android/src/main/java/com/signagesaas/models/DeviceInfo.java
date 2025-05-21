package com.signagesaas.models;

/**
 * Class for storing device information used during registration.
 */
public class DeviceInfo {
    private final String name;
    private final String type;
    private final String hardwareId;
    private final String ipAddress;
    private final String screenResolution;
    private final String orientation;
    private final String osVersion;
    private final String appVersion;
    
    private DeviceInfo(Builder builder) {
        this.name = builder.name;
        this.type = builder.type;
        this.hardwareId = builder.hardwareId;
        this.ipAddress = builder.ipAddress;
        this.screenResolution = builder.screenResolution;
        this.orientation = builder.orientation;
        this.osVersion = builder.osVersion;
        this.appVersion = builder.appVersion;
    }
    
    public String getName() {
        return name;
    }
    
    public String getType() {
        return type;
    }
    
    public String getHardwareId() {
        return hardwareId;
    }
    
    public String getIpAddress() {
        return ipAddress;
    }
    
    public String getScreenResolution() {
        return screenResolution;
    }
    
    public String getOrientation() {
        return orientation;
    }
    
    public String getOsVersion() {
        return osVersion;
    }
    
    public String getAppVersion() {
        return appVersion;
    }
    
    /**
     * Builder for creating DeviceInfo instances.
     */
    public static class Builder {
        private String name;
        private String type;
        private String hardwareId;
        private String ipAddress;
        private String screenResolution;
        private String orientation = "landscape";
        private String osVersion;
        private String appVersion;
        
        public Builder setName(String name) {
            this.name = name;
            return this;
        }
        
        public Builder setType(String type) {
            this.type = type;
            return this;
        }
        
        public Builder setHardwareId(String hardwareId) {
            this.hardwareId = hardwareId;
            return this;
        }
        
        public Builder setIpAddress(String ipAddress) {
            this.ipAddress = ipAddress;
            return this;
        }
        
        public Builder setScreenResolution(String screenResolution) {
            this.screenResolution = screenResolution;
            return this;
        }
        
        public Builder setOrientation(String orientation) {
            this.orientation = orientation;
            return this;
        }
        
        public Builder setOsVersion(String osVersion) {
            this.osVersion = osVersion;
            return this;
        }
        
        public Builder setAppVersion(String appVersion) {
            this.appVersion = appVersion;
            return this;
        }
        
        public DeviceInfo build() {
            if (name == null) {
                throw new IllegalStateException("Device name is required");
            }
            
            if (type == null) {
                throw new IllegalStateException("Device type is required");
            }
            
            if (hardwareId == null) {
                throw new IllegalStateException("Hardware ID is required");
            }
            
            return new DeviceInfo(this);
        }
    }
}