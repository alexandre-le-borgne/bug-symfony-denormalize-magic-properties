<?php

namespace App\Controller;

abstract class TopicDto
{
    private string $topicName;
    private string|null $topicMessageKey;
    private int $topicMessageOffset;

    final public function setTopicInfo(string $name, string|null $key, int $offset): void
    {
        $this->topicName = $name;
        $this->topicMessageKey = $key;
        $this->topicMessageOffset = $offset;
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function getTopicMessageKey(): string|null
    {
        return $this->topicMessageKey;
    }

    public function getTopicMessageOffset(): int
    {
        return $this->topicMessageOffset;
    }
}