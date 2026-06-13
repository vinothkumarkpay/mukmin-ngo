$src = 'C:\Users\vinod\Downloads\Mukmin_eposter_Folder-20260610T074602Z-3-001\Mukmin_eposter_Folder\Links'
$dest = 'C:\Code\mukmin1\public\welfare\img\mfls\partners'

if (-not (Test-Path $src)) {
    Write-Error "Source folder not found: $src"
    exit 1
}

New-Item -ItemType Directory -Force -Path $dest | Out-Null

$map = @{
    'bac.webp'      = 'IMG_9767.JPG.webp'
    'iact.webp'     = 'IMG_9767.JPG.webp'
    'unimy.webp'    = 'IMG_9767.JPG.webp'
    'veritas.webp'  = 'IMG_9767.JPG.webp'
    'reliance.webp' = 'IMG_9767.JPG.webp'
    'binary.webp'   = 'IMG_9770.JPG.webp'
    'unitar.webp'   = 'IMG_9771.JPG.webp'
    'uoc.webp'      = 'IMG_9768.JPG.webp'
    'mahsa.webp'    = 'IMG_9769.JPG.webp'
}

foreach ($entry in $map.GetEnumerator()) {
    $sourceFile = Join-Path $src $entry.Value
    $targetFile = Join-Path $dest $entry.Key
    if (-not (Test-Path $sourceFile)) {
        Write-Error "Missing source file: $sourceFile"
        exit 1
    }
    Copy-Item $sourceFile $targetFile -Force
}

Get-ChildItem $dest -Filter '*.webp' | Format-Table Name, Length -AutoSize
