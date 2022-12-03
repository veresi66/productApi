<?php

namespace app;

class ErrorHandler
{
    /**
     * @param \Throwable $exception
     * @return void
     */
    public static function handleException(\Throwable $exception) : void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return bool
     * @throws \ErrorException
     */
    public function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ) : bool
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
