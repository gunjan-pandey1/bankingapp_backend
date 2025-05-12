<?php

class KafkaConfig {
    private static $broker = 'localhost:9092'; // Change this to your Kafka broker address

    public static function getProducerConfig() {
        return [
            'bootstrap.servers' => self::$broker,
            'key.serializer' => 'org.apache.kafka.common.serialization.StringSerializer',
            'value.serializer' => 'org.apache.kafka.common.serialization.StringSerializer',
        ];
    }

    public static function getConsumerConfig($groupId) {
        return [
            'bootstrap.servers' => self::$broker,
            'group.id' => $groupId,
            'key.deserializer' => 'org.apache.kafka.common.serialization.StringDeserializer',
            'value.deserializer' => 'org.apache.kafka.common.serialization.StringDeserializer',
            'auto.offset.reset' => 'earliest',
        ];
    }
}