<?php

namespace Dadata;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Response;

abstract class BaseTest extends \PHPUnit\Framework\TestCase
{
    protected $api;
    protected $mock;
    protected $history;
    protected $handler;

    protected function setUp(): void
    {
        $this->mock = new MockHandler();
        $this->handler = HandlerStack::create($this->mock);
        $this->history = [];
        $history = Middleware::history($this->history);
        $this->handler->push($history);
    }

    protected function mockResponse($data)
    {
        $body = Utils::streamFor(json_encode($data));
        $response = new Response(200, [], $body);
        $this->mock->append($response);
    }

    protected function getLastRequest()
    {
        $request = $this->history[count($this->history) - 1]["request"];
        $body = $request->getBody();
        $body->rewind();
        return json_decode($body->getContents(), true);
    }
}
