<?php

namespace TeacherAi\Integration\Infrastructure\Client;

use GuzzleHttp\Client;

class OpenAi
{
    public function __construct(
        public Client $client,
    ) {
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
