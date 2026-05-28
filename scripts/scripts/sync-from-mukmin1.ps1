# Sync local dev copy (mukmin1) into the git repo / server copy (mukmin-ngo).
# Run from repo root: .\scripts\sync-from-mukmin1.ps1

$Source = "C:\Code\mukmin1"
$Target = "C:\Code\mukmin-ngo"

if (-not (Test-Path $Source)) {
    Write-Error "Source not found: $Source"
    exit 1
}

robocopy $Source $Target /E `
    /XD vendor node_modules .git storage bootstrap\cache `
    /XF .env .env.backup `
    /NFL /NDL /NJH /NJS

# robocopy: 0-7 = success
if ($LASTEXITCODE -ge 8) {
    Write-Error "Robocopy failed with exit code $LASTEXITCODE"
    exit 1
}

Write-Host "Synced $Source -> $Target"
Write-Host "Next: cd $Target; git status; git add -A; git commit; git push"
