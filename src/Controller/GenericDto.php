<?php

namespace App\Controller;

class GenericDto extends TopicDto
{
    public array $params = [];

    public function __set(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return true;
    }

    public function getData(): array
    {
        return array_merge(
            [
                'topic' => $this->getTopicName(),
                'topicMessageKey' => $this->getTopicMessageKey(),
                'topicMessageOffset' => $this->getTopicMessageOffset(),
            ],
            $this->params,
        );
    }
}
