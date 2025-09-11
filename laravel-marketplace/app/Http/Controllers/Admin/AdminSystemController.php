<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminSystemController extends Controller
{
    /**
     * Display system dashboard
     */
    public function index()
    {
        $systemHealth = $this->getSystemHealth();
        $serverInfo = $this->getServerInfo();
        $databaseInfo = $this->getDatabaseInfo();

        return view('admin.system.index', compact(
            'systemHealth', 'serverInfo', 'databaseInfo'
        ));
    }

    /**
     * Get system health status
     */
    private function getSystemHealth()
    {
        $health = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageConnection(),
        ];

        $overallHealth = collect($health)->every(fn($status) => $status === 'healthy') ? 'healthy' : 'warning';

        return [
            'overall' => $overallHealth,
            'checks' => $health,
        ];
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'healthy';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Check cache connection
     */
    private function checkCacheConnection()
    {
        try {
            Cache::put('health_check', 'ok', 1);
            $result = Cache::get('health_check');
            return $result === 'ok' ? 'healthy' : 'error';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Check storage connection
     */
    private function checkStorageConnection()
    {
        try {
            Storage::disk('local')->put('health_check.txt', 'ok');
            $result = Storage::disk('local')->get('health_check.txt');
            Storage::disk('local')->delete('health_check.txt');
            return $result === 'ok' ? 'healthy' : 'error';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Get server information
     */
    private function getServerInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
        ];
    }

    /**
     * Get database information
     */
    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            $version = $connection->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            
            return [
                'driver' => $driver,
                'version' => $version,
                'status' => 'connected',
            ];
        } catch (\Exception $e) {
            return [
                'driver' => 'Unknown',
                'version' => 'Unknown',
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache(Request $request)
    {
        $type = $request->get('type', 'all');

        try {
            switch ($type) {
                case 'config':
                    Artisan::call('config:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'cache':
                    Artisan::call('cache:clear');
                    break;
                case 'all':
                default:
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');
                    break;
            }

            Log::info('Admin cleared cache', [
                'admin_id' => auth()->id(),
                'cache_type' => $type,
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' cache cleared successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            
            Log::info('Admin ran migrations', [
                'admin_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database migrations completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to run migrations: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system statistics
     */
    public function getStatistics()
    {
        $stats = [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_limit' => ini_get('memory_limit'),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}