<?php declare(strict_types=1);

namespace Cspray\MarkedLogs;

final class CompositeMarker {

    private readonly array $markers;

    public function __construct(
        Marker $marker,
        Marker... $additionalMarkers
    ) {
        $this->markers = [$marker, ...$additionalMarkers];
    }

    /**
     * @return non-empty-list<Marker>
     */
    public function getMarkers() : array {
        return $this->markers;
    }

}
