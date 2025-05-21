using System;
using System.IO;
using Microsoft.Extensions.Logging;

namespace SignageSaaS.Utils
{
    public class SecureStorageException : Exception
    {
        public SecureStorageException(string message) : base(message) { }
        public SecureStorageException(string message, Exception inner) : base(message, inner) { }
    }

    /// <summary>
    /// Handles secure storage of device tokens (simple file-based for now).
    /// </summary>
    public static class SecureStorage
    {
        private static readonly string TokenFile = "device_token.txt";
        private static readonly ILogger Logger = new ConsoleLogger();

        public static void SaveToken(string token)
        {
            try
            {
                File.WriteAllText(TokenFile, token);
                Logger.LogInformation("Device token saved securely.");
            }
            catch (Exception ex)
            {
                Logger.LogError($"Failed to save token: {ex.Message}");
                throw new SecureStorageException("Failed to save token", ex);
            }
        }

        public static string GetToken()
        {
            try
            {
                if (!File.Exists(TokenFile))
                    return null;
                var token = File.ReadAllText(TokenFile).Trim();
                Logger.LogInformation("Device token loaded from secure storage.");
                return token;
            }
            catch (Exception ex)
            {
                Logger.LogError($"Failed to load token: {ex.Message}");
                throw new SecureStorageException("Failed to load token", ex);
            }
        }
    }
} 