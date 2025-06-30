<?php

class Logger
{
    private static string $logDir = __DIR__ . '/../../logs/';
    private static int $maxSize = 2 * 1024 * 1024; // 2MB
    private static int $maxFiles = 20;

    public static function logActivity(string $activity = "", int $userId = 0): void
    {
        $message = "[UserID:{$userId}] {$activity}";
        self::writeLog('activity.log', $message, 'INFO');
    }


    public static function logQuery(string $query, array $params = []): void
    {
        if (!defined('DEBUG_MODE') || DEBUG_MODE !== true) return;

        $log = "=== DEBUG QUERY ===\n";
        $log .= "Query  : {$query}\n";
        $log .= "Params : " . json_encode($params) . "\n";
        $log .= "====================";
        self::writeLog('db.log', $log, 'DEBUG');
    }

    public static function logError(\Throwable $e, string $context = '', int $userId = 0): void
    {
        $message = "=== ERROR ===\n";
        $message .= "UserID : {$userId}\n";
        $message .= "Code   : " . $e->getCode() . "\n";
        $message .= "Context: {$context}\n";
        $message .= "Error  : " . $e->getMessage() . "\n";
        $message .= "File   : " . $e->getFile() . " (Line " . $e->getLine() . ")\n";
        $message .= "Trace  :\n" . $e->getTraceAsString() . "\n";
        $message .= "====================";
        self::writeLog('db-error.log', $message, 'ERROR');
    }

    public static function log(string $level, string $message): void
    {
        self::writeLog('app.log', $message, strtoupper($level));
    }

    private static function writeLog(string $filename, string $message, string $level = 'INFO'): void
    {
        $logDir = self::$logDir;
        if (!is_dir($logDir) && !mkdir($logDir, 0755, true)) {
            error_log("Logger: Gagal membuat folder log: {$logDir}");
            return;
        }

        $filePath = $logDir . $filename;

        // Rotasi jika ukuran melebihi batas
        if (file_exists($filePath) && filesize($filePath) >= self::$maxSize) {
            $timestamp = date('Ymd-His');
            $rotatedFile = "{$logDir}{$filename}-{$timestamp}.log";
            if (!@rename($filePath, $rotatedFile)) {
                error_log("Logger: Gagal merotate log file: {$filePath}");
            } else {
                @file_put_contents($filePath, "[{$timestamp}] Log di-rotate\n", FILE_APPEND | LOCK_EX);
            }
        }

        // Timezone standar (pastikan konsisten)
        $time = (new \DateTime('now', new \DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $finalMessage = "[{$time}] \n[{$level}] IP:{$ip} {$message}\n\n";

        if (@file_put_contents($filePath, $finalMessage, FILE_APPEND | LOCK_EX) === false) {
            error_log("Logger: Gagal menulis log ke {$filePath}");
        }

        // Hapus file log lama jika melebihi batas
        self::cleanupOldLogs($filename);
    }

    private static function cleanupOldLogs(string $filename): void
    {
        $pattern = self::$logDir . "{$filename}-*.log";
        $logFiles = glob($pattern);

        if ($logFiles && count($logFiles) > self::$maxFiles) {
            usort($logFiles, fn($a, $b) => filemtime($a) - filemtime($b));
            $oldFiles = array_slice($logFiles, 0, count($logFiles) - self::$maxFiles);
            foreach ($oldFiles as $oldFile) {
                @unlink($oldFile);
            }
        }
    }
}
