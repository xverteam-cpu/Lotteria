# Windows helper script to initialize Laravel in this folder
# Usage: Open PowerShell as Administrator and run `./setup-laravel.ps1`

Param()

function Has-Command($name){
    return (Get-Command $name -ErrorAction SilentlyContinue) -ne $null
}

if (Has-Command "composer") {
    Write-Host "Composer found — creating Laravel project locally..."
    composer create-project laravel/laravel . --prefer-dist
    Copy-Item -Path .env.example -Destination .env -Force -ErrorAction SilentlyContinue
    composer install
    php artisan key:generate
    Write-Host "Done. Run 'php artisan serve' or use Docker as described in README.md"
    exit 0
}

if (Has-Command "docker") {
    Write-Host "Composer not found but Docker is available — using docker-compose composer service..."
    docker-compose up -d db
    docker-compose run --rm composer create-project laravel/laravel . --prefer-dist
    docker-compose up -d
    docker-compose exec app bash -lc "php artisan key:generate"
    Write-Host "Done. Open http://localhost:8000"
    exit 0
}

Write-Host "Neither Composer nor Docker were found. Install Composer (https://getcomposer.org/) or Docker Desktop, then re-run this script."
exit 1
