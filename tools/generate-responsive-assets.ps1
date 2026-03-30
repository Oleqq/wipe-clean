$ErrorActionPreference = "Stop"

function Invoke-SharpVariant {
    param(
        [Parameter(Mandatory = $true)][string]$SourcePath,
        [Parameter(Mandatory = $true)][ValidateSet("png", "webp")][string]$Format,
        [Parameter(Mandatory = $true)][int]$Width,
        [Parameter(Mandatory = $true)][string]$Target
    )

    $tempDir = ".codex-img-build"
    New-Item -ItemType Directory -Force -Path $tempDir | Out-Null

    $resolvedInput = $SourcePath
    $targetPath = $Target
    $targetDir = Split-Path -Parent $targetPath
    $baseName = [System.IO.Path]::GetFileNameWithoutExtension($SourcePath)
    $builtFile = Join-Path $tempDir ($baseName + "." + $Format)

    if (Test-Path $builtFile) {
        Remove-Item $builtFile -Force
    }

    & npx --yes sharp-cli@4.1.1 -i $resolvedInput -o $tempDir -f $Format resize $Width | Out-Null

    New-Item -ItemType Directory -Force -Path $targetDir | Out-Null
    Move-Item -Force $builtFile $targetPath
}

function Invoke-SharpOriginalWebp {
    param(
        [Parameter(Mandatory = $true)][string]$SourcePath,
        [Parameter(Mandatory = $true)][string]$Target
    )

    $tempDir = ".codex-img-build"
    New-Item -ItemType Directory -Force -Path $tempDir | Out-Null

    $resolvedInput = $SourcePath
    $targetPath = $Target
    $targetDir = Split-Path -Parent $targetPath
    $baseName = [System.IO.Path]::GetFileNameWithoutExtension($SourcePath)
    $builtFile = Join-Path $tempDir ($baseName + ".webp")

    if (Test-Path $builtFile) {
        Remove-Item $builtFile -Force
    }

    & npx --yes sharp-cli@4.1.1 -i $resolvedInput -o $tempDir -f webp | Out-Null

    New-Item -ItemType Directory -Force -Path $targetDir | Out-Null
    Move-Item -Force $builtFile $targetPath
}

$jobs = @(
    @{ input = "static/images/section/home-hero/hero-room.png"; smallWidth = 728; smallFallback = "static/images/section/home-hero/hero-room-728.png"; smallWebp = "static/webp-images/section/home-hero/hero-room-728.webp"; largeWebp = "static/webp-images/section/home-hero/hero-room-1456.webp" },
    @{ input = "static/images/section/home-hero/hero-cleaner.png"; smallWidth = 464; smallFallback = "static/images/section/home-hero/hero-cleaner-464.png"; smallWebp = "static/webp-images/section/home-hero/hero-cleaner-464.webp"; largeWebp = "static/webp-images/section/home-hero/hero-cleaner-928.webp" },
    @{ input = "static/images/section/about-hero/about-hero-image.png"; smallWidth = 512; smallFallback = "static/images/section/about-hero/about-hero-image-512.png"; smallWebp = "static/webp-images/section/about-hero/about-hero-image-512.webp"; largeWebp = "static/webp-images/section/about-hero/about-hero-image-1024.webp" },
    @{ input = "static/images/section/services-hero/services-hero-cleaner.png"; smallWidth = 468; smallFallback = "static/images/section/services-hero/services-hero-cleaner-468.png"; smallWebp = "static/webp-images/section/services-hero/services-hero-cleaner-468.webp"; largeWebp = "static/webp-images/section/services-hero/services-hero-cleaner-936.webp" },
    @{ input = "static/images/section/services-hero/services-hero-interior.png"; smallWidth = 630; smallFallback = "static/images/section/services-hero/services-hero-interior-630.png"; smallWebp = "static/webp-images/section/services-hero/services-hero-interior-630.webp"; largeWebp = "static/webp-images/section/services-hero/services-hero-interior-1260.webp" },
    @{ input = "static/images/section/service-hero/service-hero-apartment-cleaning.png"; smallWidth = 768; smallFallback = "static/images/section/service-hero/service-hero-apartment-cleaning-768.png"; smallWebp = "static/webp-images/section/service-hero/service-hero-apartment-cleaning-768.webp"; largeWebp = "static/webp-images/section/service-hero/service-hero-apartment-cleaning-1536.webp" },
    @{ input = "static/images/section/prices-hero/prices-hero-left.png"; smallWidth = 768; smallFallback = "static/images/section/prices-hero/prices-hero-left-768.png"; smallWebp = "static/webp-images/section/prices-hero/prices-hero-left-768.webp"; largeWebp = "static/webp-images/section/prices-hero/prices-hero-left-1536.webp" },
    @{ input = "static/images/section/prices-hero/prices-hero-right.png"; smallWidth = 768; smallFallback = "static/images/section/prices-hero/prices-hero-right-768.png"; smallWebp = "static/webp-images/section/prices-hero/prices-hero-right-768.webp"; largeWebp = "static/webp-images/section/prices-hero/prices-hero-right-1536.webp" },
    @{ input = "static/images/section/blog-archive/blog-archive-hero.png"; smallWidth = 768; smallFallback = "static/images/section/blog-archive/blog-archive-hero-768.png"; smallWebp = "static/webp-images/section/blog-archive/blog-archive-hero-768.webp"; largeWebp = "static/webp-images/section/blog-archive/blog-archive-hero-1536.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-1-photo-1.png"; smallWidth = 500; smallFallback = "static/images/section/gallery-preview/gallery-row-1-photo-1-500.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-1-500.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-1-1000.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-1-photo-2.png"; smallWidth = 640; smallFallback = "static/images/section/gallery-preview/gallery-row-1-photo-2-640.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-2-640.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-2-1280.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-1-photo-3.png"; smallWidth = 320; smallFallback = "static/images/section/gallery-preview/gallery-row-1-photo-3-320.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-3-320.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-1-photo-3-640.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-1-video-poster.png"; smallWidth = 960; smallFallback = "static/images/section/gallery-preview/gallery-row-1-video-poster-960.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-1-video-poster-960.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-1-video-poster-1920.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-2-photo-1.png"; smallWidth = 341; smallFallback = "static/images/section/gallery-preview/gallery-row-2-photo-1-341.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-1-341.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-1-683.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-2-video-poster.png"; smallWidth = 284; smallFallback = "static/images/section/gallery-preview/gallery-row-2-video-poster-284.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-2-video-poster-284.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-2-video-poster-568.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-2-photo-2.png"; smallWidth = 1000; smallFallback = "static/images/section/gallery-preview/gallery-row-2-photo-2-1000.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-2-1000.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-2-2000.webp" },
    @{ input = "static/images/section/gallery-preview/gallery-row-2-photo-3.png"; smallWidth = 540; smallFallback = "static/images/section/gallery-preview/gallery-row-2-photo-3-540.png"; smallWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-3-540.webp"; largeWebp = "static/webp-images/section/gallery-preview/gallery-row-2-photo-3-1080.webp" }
)

foreach ($job in $jobs) {
    Invoke-SharpVariant -SourcePath $job.input -Format "png" -Width $job.smallWidth -Target $job.smallFallback
    Invoke-SharpVariant -SourcePath $job.input -Format "webp" -Width $job.smallWidth -Target $job.smallWebp
    Invoke-SharpOriginalWebp -SourcePath $job.input -Target $job.largeWebp
}

Get-ChildItem "static/webp-images/section" -Recurse -File | Measure-Object | Select-Object -ExpandProperty Count
