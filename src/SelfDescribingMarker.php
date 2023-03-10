<?php declare(strict_types=1);

namespace Cspray\MarkedLogs;

abstract class SelfDescribingMarker implements Marker {

    final public function toString() : string {
        return static::class;
    }

}
