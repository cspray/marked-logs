# Marked Logs

Provides a PSR-3 Logger decorator that ensures every logged message is marked with an identifier to easily group similar log messages and find the logs you're looking for.

## Installation

[Composer](https://getcomposer.org) is the only supported method for installing this library.

```
composer require cspray/marked-logs
```

## Usage Guide

Imagine a scenario where you're interacting with a RESTful API and you want to make sure you have extensive logs. Marked Logs provides a way to easily group these logs together to gain a picture for all the interactions made with the API. Let's take a look at some example code.

```php
<?php declare(strict_types=1);

namespace Acme\MarkedLogsDemo;

use Cspray\MarkedLogs\Marker;
use Cspray\MarkedLogs\MarkedLogger;
use Psr\Log\LoggerInterface;
use Psr\Http\Client\ClientInterface;

// Make sure you make this a meaningful name! It will be what shows up as the marker in your logs
class RestfulApiMarker implements Marker {}

class WidgetService {

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ClientInterface $client,
        LoggerInterface $logger
    ) {
        // Here's the part where you actually interact with Marked Logs!
        $this->logger = new MarkedLogger(new RestfulApiMarker(), $logger);
    }

    public function fetch(string $id) : Widget {
        $this->logger->info('Fetching widget with id {id}', ['id' => $id]);
        
        // Rest of client interactions below
        $this->client->sendRequest( ... );
    }

    public function update(Widget $widget) : Widget {
        $this->logger->info('Updating widget with id {id}', ['id' => $widget->id]);
        
        // Rest of client interactions below
        $this->client->sendRequest( ... );
    }

    public function remove(Widget $widget) : void {
        $this->logger->info('Removing widget with id {id}', ['id' => $widget->id]); 
        
        // Rest of client interactions below
        $this->client->sendRequest( ... );
    }
}

// Now... let's use it!

$service = new WidgetService(
    YourHttpClientFactory::create(),
    YourLoggerFactory::create()
);

$widget = $service->fetch('1234');
$widget->withState(WidgetState::Done);
$widget = $service->update($widget);
$service->remove($widget);

// Logged Messages and Context would look like

// record 0: "Fetching widget with id 1234", ['id' => '1234', 'marker' => ['Acme\MarkedLogsDemo\RestfulApiMarker']]
// record 1: "Updating widget with id 1234", ['id' => '1234', 'marker' => ['Acme\MarkedLogsDemo\RestfulApiMarker']]
// record 2: "Removing widget with id 1234", ['id' => '1234', 'marker' => ['Acme\MarkedLogsDemo\RestfulApiMarker']]
```

Now you can see all the logs for interacting with the RESTful API by searching for the provided Marker in your logs aggregator!

## Multiple Markers

The `RestfulApiMarker` introduced above has proven useful and other endpoints besides widgets are starting to use it. This 
is beneficial if you want to know the overall picture of API use, but kind of noisy if you just care about Widgets. Multiple 
markers can help solve this proble, and you can do just that by making use of the `Cspray\MarkedLogs\CompositeMarker` object! 
Keeping the same Marker we had above, we're gonna add one more and update the argument passed to the `MarkedLogger`.

```php
<?php declare(strict_types=1);

namespace Acme\MarkedLogsDemo;

use Cspray\MarkedLogs\CompositeMarker;use Cspray\MarkedLogs\Marker;
use Cspray\MarkedLogs\MarkedLogger;
use Psr\Log\LoggerInterface;
use Psr\Http\Client\ClientInterface;

class RestfulApiMarker implements Marker {}

class WidgetApiMarker implements Marker {}

class WidgetService {

    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly ClientInterface $client,
        LoggerInterface $logger
    ) {
        // Here's the part where you actually interact with Marked Logs!
        $this->logger = new MarkedLogger(new CompositeMarker(new RestfulApiMarker(), new WidgetApiMarker()), $logger);
    }
    
    # rest of class as it appears above...
    
}
```

Now our marker would include both class names provided, allowing you to keep these logs in the greater overall context of 
API interactions and also allow you to drill down further into specific endpoints.

Happy logging!