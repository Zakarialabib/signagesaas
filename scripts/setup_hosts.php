<?php

declare(strict_types=1);

/**
 * This script helps set up host entries for tenant domains.
 * Run it with PHP to add entries to your hosts file.
 *
 * Usage: php scripts/setup_hosts.php
 *
 * Note: On Windows, this script needs to be run with administrator privileges.
 */

// Configuration
$baseHostname = 'signagesaas.test';
$hostsFilePath = getHostsFilePath();
$tenantSlugs = ['tenant1', 'tenant2', 'demo', 'test']; // Add your tenant slugs here

// Main execution
echo "SignageSaaS Hosts File Setup\n";
echo "============================\n";

// Check for admin privileges
if ( ! isRunningAsAdmin()) {
    echo "Error: This script needs to be run with administrator privileges.\n";
    echo "Please restart the script with admin rights.\n";
    exit(1);
}

// Read current hosts file
if ( ! file_exists($hostsFilePath)) {
    echo "Error: Hosts file not found at {$hostsFilePath}\n";
    exit(1);
}

$hostsContent = file_get_contents($hostsFilePath);

if ($hostsContent === false) {
    echo "Error: Could not read hosts file at {$hostsFilePath}\n";
    exit(1);
}

// Check if base hostname is in hosts file
if ( ! hasHostEntry($hostsContent, $baseHostname)) {
    echo "Adding base domain {$baseHostname}...\n";
    $hostsContent = addHostEntry($hostsContent, $baseHostname);
}

// Check and add tenant domains
$addedCount = 0;

foreach ($tenantSlugs as $slug) {
    $tenantDomain = "{$slug}.{$baseHostname}";

    if ( ! hasHostEntry($hostsContent, $tenantDomain)) {
        echo "Adding tenant domain {$tenantDomain}...\n";
        $hostsContent = addHostEntry($hostsContent, $tenantDomain);
        $addedCount++;
    }
}

// Write back hosts file if changes were made
if ($addedCount > 0) {
    echo "Writing changes to hosts file...\n";

    if (file_put_contents($hostsFilePath, $hostsContent) === false) {
        echo "Error: Could not write to hosts file. Check permissions.\n";
        exit(1);
    }
    echo "Successfully added {$addedCount} host entries.\n";
} else {
    echo "All domains are already in your hosts file. No changes made.\n";
}

echo "\nDone! Your hosts file is now configured for SignageSaaS.\n";
echo "Remember to restart Laragon for changes to take effect.\n";

// Helper functions
function getHostsFilePath()
{
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
        ? 'C:\\Windows\\System32\\drivers\\etc\\hosts'
        : '/etc/hosts';
}

function isRunningAsAdmin()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Check if running as administrator on Windows
        exec('net session 2>&1', $output, $returnCode);

        return $returnCode === 0;
    } else {
        // Check if running as root on Unix
        return posix_getuid() === 0;
    }
}

function hasHostEntry($hostsContent, $hostname)
{
    $pattern = '/^\s*127\.0\.0\.1\s+'.preg_quote($hostname, '/').'\s*$/m';

    return preg_match($pattern, $hostsContent) === 1;
}

function addHostEntry($hostsContent, $hostname)
{
    // Add newline if file doesn't end with one
    if (substr($hostsContent, -1) !== "\n") {
        $hostsContent .= "\n";
    }

    // Add the host entry
    $hostsContent .= "127.0.0.1\t{$hostname}\n";

    return $hostsContent;
}
