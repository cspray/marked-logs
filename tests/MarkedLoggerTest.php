<?php declare(strict_types=1);

namespace Cspray\MarkedLogs\Test;

use Cspray\MarkedLogs\MarkedLogger;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

#[CoversClass(MarkedLogger::class)]
class MarkedLoggerTest extends TestCase {

    public function logMethodsProvider() : array {
        return [
            'emergency' => [Level::Emergency, 'emergency'],
            'alert' => [Level::Alert, 'alert'],
            'critical' => [Level::Critical, 'critical',],
            'error' => [Level::Error, 'error',],
            'warning' => [Level::Warning, 'warning',],
            'notice' => [Level::Notice, 'notice',],
            'info' => [Level::Info, 'info',],
            'debug' => [Level::Debug, 'debug',],
            'log' => [Level::Info, 'log', [LogLevel::INFO]]
        ];
    }

    #[DataProvider('logMethodsProvider')]
    public function testLoggedRecordHasMarkedContext(Level $level, string $method, array $args = []) : void {
        $subject = new MarkedLogger(
            new StubMarker(),
            new Logger('marked-logs.test', [$testHandler = new TestHandler()])
        );
        $subject->$method(...[...$args, 'My known log message', ['my-context-key' => 'known-val']]);

        $records = $testHandler->getRecords();

        self::assertCount(1, $records);
        self::assertSame($level, $records[0]->level);
        self::assertSame('My known log message', $records[0]->message);
        self::assertSame(['my-context-key' => 'known-val', 'marker' => StubMarker::class], $records[0]->context);
    }

    #[DataProvider('logMethodsProvider')]
    public function testLoggedRecordRespectsDesiredMarkerKey(Level $level, string $method, array $args = []) : void {
        $subject = new MarkedLogger(
            new StubMarker(),
            new Logger('marked-logs.test', [$testHandler = new TestHandler()]),
            'MY-marker-key'
        );
        $subject->$method(...[...$args, 'My known log message', ['my-context-key' => 'known-val']]);

        $records = $testHandler->getRecords();

        self::assertCount(1, $records);
        self::assertSame($level, $records[0]->level);
        self::assertSame('My known log message', $records[0]->message);
        self::assertSame(['my-context-key' => 'known-val', 'MY-marker-key' => StubMarker::class], $records[0]->context);
    }

}
