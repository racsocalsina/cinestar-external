<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Trait TrackerLog
 *
 * @package App\Traits
 */
trait TrackerLog
{
    /**
     * @param $exception
     * @param $file
     */
    protected function loggerError($exception, $file): void
    {
        $now = Carbon::now('America/Lima')->toDateTimeString();

        $message = '';
        $message .= '[' . $now . ']: ' . $exception->getFile() . "\n";
        $message .= '[' . $now . ']: ' . $exception->getLine() . "\n";
        $message .= '[' . $now . ']: ' . $exception->getMessage() . "\n";

        Storage::disk('logger')->append($file, $message);
    }
}