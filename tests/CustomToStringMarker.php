<?php declare(strict_types=1);

namespace Cspray\MarkedLogs\Test;

use Cspray\MarkedLogs\Marker;

class CustomToStringMarker implements Marker {

    public function toString() : string {
        return 'MyCustomMarker';
    }
}