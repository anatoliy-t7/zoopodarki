@setup
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

try {
$dotenv->load();
$dotenv->required(['DEPLOY_USER', 'DEPLOY_SERVER', 'DEPLOY_BASE_DIR', 'DEPLOY_REPO'])->notEmpty();
} catch ( Exception $e ) {
echo $e->getMessage();
}

$user = env('DEPLOY_USER');
$repo = env('DEPLOY_REPO');
$server = env('DEPLOY_SERVER');

if (!isset($baseDir)) {
$baseDir = env('DEPLOY_BASE_DIR');
}

$branchOrTag = env('CI_COMMIT_TAG');
if (!$branchOrTag) {
$branchOrTag = env('CI_COMMIT_BRANCH', 'main');
}

$releaseDir = $baseDir . '/releases';
$currentDir = $baseDir . '/current';
$release = date('YmdHis');
$currentReleaseDir = $releaseDir . '/' . $release;


$productionPort = 22;
$productionHost = $user . '@' . $server;

function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1', 'prod' => env('DEPLOY_USER').'@'.env('DEPLOY_SERVER')])

@task('rollback', ['on' => 'prod', 'confirm' => true])
{{ logMessage('Rolling back...') }}
cd {{ $releaseDir }}
ln -nfs {{ $releaseDir }}/$(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1) {{ $baseDir }}/current
{{ logMessage('Rolled back!') }}

{{ logMessage('Rebuilding cache') }}
php {{ $currentDir }}/artisan optimize
{{ logMessage('Rebuilding cache completed') }}

echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1)"
@endtask

@task('init', ['on' => 'prod', 'confirm' => true])
if [ ! -d {{ $baseDir }}/current ]; then
cd {{ $baseDir }}

git clone {{ $repo }} --branch={{ $branchOrTag }} --depth=1 -q {{ $release }}
{{ logMessage('Repository cloned') }}

mv {{ $release }}/storage {{ $baseDir }}/storage
ln -nfs {{ $baseDir }}/storage {{ $release }}/storage
ln -nfs {{ $baseDir }}/storage/public {{ $release }}/public/storage
{{ logMessage('Storage directory set up') }}

cp {{ $release }}/.env.example {{ $baseDir }}/.env
ln -nfs {{ $baseDir }}/.env {{ $release }}/.env
{{ logMessage('Environment file set up') }}

sudo chown -R {{ $user }}:facknetr {{ $baseDir }}/storage
sudo chmod -R ug+rwx {{ $baseDir }}/storage

rm -rf {{ $release }}
{{ logMessage("Deployment path initialised. Run 'envoy run deploy' now.") }}
else
{{ logMessage('Deployment path already initialised (current symlink exists)!') }}
fi
@endtask

@story('deploy')
git
composer
npm_install
npm_run_prod
update_symlinks
migrate_release
set_permissions
reload_services
cache
clean_old_releases
@endstory

@task('git', ['on' => 'prod'])
{{ logMessage('Cloning repository') }}

git clone {{ $repo }} --branch={{ $branchOrTag }} --depth=1 -q {{ $currentReleaseDir }}
@endtask

@task('composer', ['on' => 'prod'])
{{ logMessage('Running composer') }}

cd {{ $currentReleaseDir }}
mkdir -p storage/framework/{session,views,cache}
chmod -R ug+rwx storage
chown -R {{ $user }}:facknetr storage
chmod -R ug+rwx storage/framework
chown -R {{ $user }}:facknetr storage/framework

composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
@endtask

@task('npm_install', ['on' => 'local'])
{{ logMessage('NPM install') }}

npm install --silent --no-progress > /dev/null

@endtask

@task('npm_run_prod', ['on' => 'local'])
{{ logMessage('NPM run production') }}

npm run production --silent --no-progress > /dev/null

@endtask

@task('assets', ['on' => 'local'])
{{ logMessage('Production assets started to move to prod server') }}
scp -P{{ $productionPort }} -qr public/css {{ $productionHost }}:{{ $currentReleaseDir }}/public
scp -P{{ $productionPort }} -qr public/js {{ $productionHost }}:{{ $currentReleaseDir }}/public
scp -P{{ $productionPort }} -q public/mix-manifest.json {{ $productionHost }}:{{ $currentReleaseDir }}/public
{{ logMessage('Production assets moved') }}
@endtask

@task('update_symlinks', ['on' => 'prod'])
{{ logMessage('Updating symlinks') }}

# Remove the storage directory and replace with persistent data
{{ logMessage('Linking storage directory') }}
cd {{ $currentReleaseDir }};
ln -nfs {{ $baseDir }}/storage {{ $currentReleaseDir }}/storage;
ln -nfs {{ $baseDir }}/storage/app/public {{ $currentReleaseDir }}/public/storage

# Remove the public uploads directory and replace with persistent data
# {{ logMessage('Linking uploads directory') }}
# rm -rf {{ $currentReleaseDir }}/public/uploads
# cd {{ $currentReleaseDir }}/public
# ln -nfs {{ $baseDir }}/uploads {{ $currentReleaseDir }}/uploads;

# Import the environment config
{{ logMessage('Linking .env file') }}
cd {{ $currentReleaseDir }};
ln -nfs {{ $baseDir }}/.env .env;

# Symlink the latest release to the current directory
{{ logMessage('Linking current release') }}
ln -nfs {{ $currentReleaseDir }} {{ $currentDir }};
@endtask

@task('set_permissions', ['on' => 'prod'])
# Set dir permissions
{{ logMessage('Set permissions') }}

sudo chown -R {{ $user }}:facknetr {{ $baseDir }}
sudo chmod -R ug+rwx {{ $baseDir }}/storage
cd {{ $baseDir }}
sudo chown -R {{ $user }}:facknetr current
sudo chmod -R ug+rwx current/storage current/bootstrap/cache
sudo chown -R {{ $user }}:facknetr {{ $currentReleaseDir }}
@endtask

@task('cache', ['on' => 'prod'])
{{ logMessage('Building cache') }}

php {{ $currentDir }}/artisan optimize
@endtask

@task('clean_old_releases', ['on' => 'prod'])
# Delete all but the 5 most recent releases
{{ logMessage('Cleaning old releases') }}
cd {{ $releaseDir }}
ls -dt {{ $releaseDir }}/* | tail -n +6 | xargs -d "\n" rm -rf;
@endtask

@task('migrate_release', ['on' => 'prod', 'confirm' => false])
{{ logMessage('Running migrations') }}

php {{ $currentReleaseDir }}/artisan migrate --force
@endtask

@task('migrate', ['on' => 'prod', 'confirm' => true])
{{ logMessage('Running migrations') }}

php {{ $currentDir }}/artisan migrate --force
@endtask

@task('migrate_rollback', ['on' => 'prod', 'confirm' => true])
{{ logMessage('Rolling back migrations') }}

php {{ $currentDir }}/artisan migrate:rollback --force
@endtask

@task('migrate_status', ['on' => 'prod'])
php {{ $currentDir }}/artisan migrate:status
@endtask

@task('reload_services', ['on' => 'prod'])
# Reload Services
{{ logMessage('Restarting service supervisor') }}
sudo supervisorctl restart all

{{ logMessage('Reloading php') }}
sudo systemctl reload php8.0-fpm
@endtask

@finished
echo "Envoy deployment script finished.\r\n";
@endfinished
