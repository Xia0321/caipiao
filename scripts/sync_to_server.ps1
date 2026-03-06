# Sync local project to server (root@154.219.120.254:/www/wwwroot/154.219.120.254)
# Run from project root: .\scripts\sync_to_server.ps1
# Loads password from scripts/sync_config.local.ps1 if present (do not commit that file).

$ErrorActionPreference = "Stop"
$remoteUser   = "root"
$remoteHost   = "154.219.120.254"
$remotePath   = "/www/wwwroot/154.219.120.254"
$localPath    = "F:\newProects\caip"

if (-not [System.IO.Path]::IsPathRooted($localPath)) {
    $localPath = Join-Path (Get-Location) $localPath
}

$configPath = Join-Path (Split-Path -Parent $PSCommandPath) "sync_config.local.ps1"
if (Test-Path $configPath) {
    . $configPath
}

$remote = $remoteUser + "@" + $remoteHost + ":" + $remotePath
Write-Host "Syncing: $localPath -> $remote" -ForegroundColor Cyan

$password = $env:SYNC_SSH_PASSWORD
$src = $localPath + "\*"

# Prefer pscp (PuTTY) so we can pass password without prompt
# -hostkey accepts server key in batch mode (fingerprint from first connection)
$hostkey = "SHA256:jKZtwadMCyQRrk32caAiwXLxqgMSKY5nfzPW3BNyi2A"
$pscp = Get-Command pscp -ErrorAction SilentlyContinue
if ($pscp -and $password) {
    & pscp -pw $password -r -batch -hostkey $hostkey "$src" $remote
} else {
    & scp -r -o StrictHostKeyChecking=no "$src" $remote
}

if ($LASTEXITCODE -eq 0) {
    Write-Host "Sync done." -ForegroundColor Green
} else {
    Write-Host "Sync failed. Check network and SSH password." -ForegroundColor Red
    exit 1
}
