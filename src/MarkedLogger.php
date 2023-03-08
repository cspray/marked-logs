<?php declare(strict_types=1);

namespace Cspray\MarkedLogs;

use Psr\Log\LoggerInterface;
use Stringable;

final class MarkedLogger implements LoggerInterface {

    public function __construct(
        private readonly Marker $marker,
        private readonly LoggerInterface $logger,
        private readonly string $markerKey = 'marker'
    ) {}

    public function emergency(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->emergency($message, $context);
    }

    public function alert(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->alert($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->critical($message, $context);
    }

    public function error(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->error($message, $context);
    }

    public function warning(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->warning($message, $context);
    }

    public function notice(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->notice($message, $context);
    }

    public function info(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->info($message, $context);
    }

    public function debug(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->debug($message, $context);
    }

    public function log($level, Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->marker::class;
        $this->logger->log($level, $message, $context);
    }
}