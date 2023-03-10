<?php declare(strict_types=1);

namespace Cspray\MarkedLogs;

use Psr\Log\LoggerInterface;
use Stringable;

final class MarkedLogger implements LoggerInterface {

    public function __construct(
        private readonly Marker|CompositeMarker $marker,
        private readonly LoggerInterface $logger,
        private readonly string $markerKey = 'marker'
    ) {}

    public function emergency(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->emergency($message, $context);
    }

    public function alert(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->alert($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->critical($message, $context);
    }

    public function error(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->error($message, $context);
    }

    public function warning(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->warning($message, $context);
    }

    public function notice(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->notice($message, $context);
    }

    public function info(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->info($message, $context);
    }

    public function debug(Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->debug($message, $context);
    }

    public function log($level, Stringable|string $message, array $context = []) : void {
        $context[$this->markerKey] = $this->getMarker();
        $this->logger->log($level, $message, $context);
    }

    private function getMarker() : array {
        $markers = [];
        if ($this->marker instanceof Marker) {
            $markers[] = $this->marker->toString();
        } else {
            foreach ($this->marker->getMarkers() as $marker) {
                $markers[] = $marker->toString();
            }
        }
        return $markers;
    }
}