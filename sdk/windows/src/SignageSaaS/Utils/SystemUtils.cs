using System;
using Microsoft.Extensions.Logging;

namespace SignageSaaS.Utils
{
    public class SystemUtilsException : Exception
    {
        public SystemUtilsException(string message) : base(message) { }
        public SystemUtilsException(string message, Exception inner) : base(message, inner) { }
    }

    /// <summary>
    /// Utility methods for system information (storage, etc.).
    /// </summary>
    public static class SystemUtils
    {
        private static readonly ILogger Logger = new ConsoleLogger();

        public static object GetStorageInfo()
        {
            try
            {
                // TODO: Implement actual storage info retrieval
                Logger.LogInformation("Retrieving storage info (stubbed)...");
                return new
                {
                    total = 1000000000L,
                    used = 500000000L,
                    free = 500000000L,
                    percent_used = 50.0
                };
            }
            catch (Exception ex)
            {
                Logger.LogError($"Failed to get storage info: {ex.Message}");
                throw new SystemUtilsException("Failed to get storage info", ex);
            }
        }
    }
} 