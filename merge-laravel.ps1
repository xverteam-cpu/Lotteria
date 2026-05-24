$src='laravel_temp'
$dest=Get-Location
$exclude=@('docker','docker-compose.yml','README.md','setup-laravel.ps1','.env.example','.gitignore')
$srcFull=(Get-Item $src).FullName
Get-ChildItem -Path $src -Recurse -Force | ForEach-Object {
    $rel = $_.FullName.Substring($srcFull.Length+1).TrimStart('\')
    $target = Join-Path $dest $rel
    if ($_.PSIsContainer) {
        if (-not (Test-Path $target)) { New-Item -ItemType Directory -Path $target | Out-Null }
    } else {
        $top = $rel.Split('\')[0]
        if ($exclude -contains $rel -or $exclude -contains $top) { Write-Host "Skipping excluded $rel"; continue }
        if (-not (Test-Path $target)) { Copy-Item -Path $_.FullName -Destination $target -Force; Write-Host "Copied: $rel" } else { Write-Host "Skipped existing: $rel" }
    }
}
