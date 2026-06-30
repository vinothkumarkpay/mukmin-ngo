$viewSrc = 'C:\Users\vinod\Downloads\view'
$logosSrc = 'C:\Users\vinod\Downloads\logos'
$dest = 'C:\Code\mukmin1\public\welfare\img\mfls\partners'

New-Item -ItemType Directory -Force -Path $dest | Out-Null

# Approved logos from Downloads\view
$viewMap = @{
    'bac.jpg'      = @('BAC.jpeg')
    'iact.jpg'     = @('IACT.jpeg')
    'veritas.jpeg' = @('VERITAS.jpeg', 'veritas.jpeg', 'Veritas.jpeg')
}

foreach ($entry in $viewMap.GetEnumerator()) {
    $sourceFile = $null
    foreach ($candidate in $entry.Value) {
        $path = Join-Path $viewSrc $candidate
        if (Test-Path $path) {
            $sourceFile = $path
            break
        }
    }

    $targetFile = Join-Path $dest $entry.Key

    if (-not $sourceFile) {
        if (Test-Path $targetFile) {
            Write-Warning "Source not found for $($entry.Key); keeping existing file at $targetFile"
            continue
        }

        Write-Error "Missing source file for $($entry.Key). Expected one of: $($entry.Value -join ', ') in $viewSrc"
        exit 1
    }

    Copy-Item $sourceFile $targetFile -Force
}

# Logos from Downloads\logos
if (Test-Path $logosSrc) {
    $logosMap = @{
        'reliance.png' = 'LOGO_Reliance_Color.png'
        'unimy.png'    = 'LOGO_UNIMY.png'
    }

    foreach ($entry in $logosMap.GetEnumerator()) {
        $sourceFile = Join-Path $logosSrc $entry.Value
        $targetFile = Join-Path $dest $entry.Key

        if (-not (Test-Path $sourceFile)) {
            if (Test-Path $targetFile) {
                Write-Warning "Source not found for $($entry.Key); keeping existing file at $targetFile"
                continue
            }

            Write-Error "Missing source file: $sourceFile"
            exit 1
        }

        Copy-Item $sourceFile $targetFile -Force
    }
}

Get-ChildItem $dest -Include bac.jpg, iact.jpg, veritas.jpeg, reliance.png, unimy.png -File | Format-Table Name, Length -AutoSize
