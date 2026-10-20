<?php

namespace App\Filters;

use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Crash-safe replacement for the framework Debug Toolbar filter.
 *
 * Root cause it works around (CI 4.6.1, vendor, do NOT patch):
 * DebugToolbar collects request/response data and json_encodes it.
 * When the data contains malformed UTF-8 bytes (e.g. an invalid tracking
 * code like /o/abc123 whose base64 decodes to binary), json_encode()
 * returns false and Toolbar::run(): string throws a TypeError, turning
 * the whole response into a 500 — even for innocent redirects.
 *
 * This wrapper keeps the toolbar wherever it works and degrades
 * gracefully (logs + skips) where it would crash the response.
 */
class SafeToolbarFilter extends DebugToolbar
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        try {
            return parent::after($request, $response, $arguments);
        } catch (\Throwable $e) {
            log_message(
                'error',
                'Debug toolbar skipped to avoid breaking response: ' .
                $e->getMessage()
            );

            return null;
        }
    }
}
