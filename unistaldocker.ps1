# Fermer les processus Docker
Get-Process *docker* -ErrorAction SilentlyContinue | Stop-Process -Force

# Désinstaller via WMI
$docker = Get-WmiObject -Class Win32_Product | Where-Object { $_.Name -eq "Docker Desktop" }
if ($docker) {
    Write-Host "Docker Desktop trouvé, désinstallation..."
    $docker.Uninstall()
} else {
    Write-Host "Docker Desktop non détecté via WMI, suppression manuelle forcée..."
}

# Supprimer les dossiers restants
$paths = @(
    "C:\Program Files\Docker",
    "$env:LOCALAPPDATA\Docker",
    "$env:APPDATA\Docker",
    "$env:USERPROFILE\.docker"
)

foreach ($path in $paths) {
    if (Test-Path $path) {
        Write-Host "Suppression du dossier : $path"
        Remove-Item -Recurse -Force -Path $path
    } else {
        Write-Host "Dossier non trouvé : $path"
    }
}

# Supprimer le service com.docker.service s'il existe
$service = Get-Service -Name "com.docker.service" -ErrorAction Silentl
