<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Mapper;

use NeuronAI\Tools\ArrayProperty;
use NeuronAI\Tools\ObjectProperty;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;
use NeuronAI\Tools\ToolPropertyInterface;
use Psr\Log\LoggerInterface;

/**
 * Maps raw property definition data to NeuronAI ToolProperty instances.
 *
 * Handles the JSON array stored in the `properties` column of gtstudio_ai_tools,
 * converting each entry into a typed ToolProperty that the LLM provider understands.
 */
class ToolPropertyMapper
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Map a single raw property definition array to the correct ToolPropertyInterface instance.
     *
     * Expected keys: `name` (string), `type` (PropertyType value string),
     * `description` (string), `required` (bool|int).
     *
     * Array and object types are mapped to ArrayProperty/ObjectProperty so that
     * LLM providers (e.g. OpenAI) receive a valid JSON Schema with `items` or
     * `properties` keys. Using ToolProperty for those types omits required fields
     * and causes a 400 Bad Request from the API.
     *
     * @param array<string, mixed> $propertyData Raw property data from the form.
     * @return ToolPropertyInterface
     */
    public function map(array $propertyData): ToolPropertyInterface
    {
        $name        = (string)$propertyData['name'];
        $description = isset($propertyData['description']) ? (string)$propertyData['description'] : null;
        $required    = (bool)($propertyData['required'] ?? false);
        $type        = (string)$propertyData['type'];

        return match ($type) {
            'array'  => new ArrayProperty($name, $description, $required),
            'object' => new ObjectProperty($name, $description, $required),
            default  => new ToolProperty($name, PropertyType::from($type), $description, $required),
        };
    }

    /**
     * Decode a JSON-encoded properties string and map each entry to a ToolProperty.
     *
     * Invalid entries (missing required keys or unknown types) are skipped with
     * a warning so a single misconfigured property does not break the whole tool.
     *
     * @param string|null $json JSON array of property definition objects.
     * @return ToolPropertyInterface[]
     */
    public function mapFromJson(?string $json): array
    {
        if (empty($json)) {
            return [];
        }

        $data = json_decode($json, true);

        if (!is_array($data)) {
            $this->logger->warning('ToolPropertyMapper: properties JSON is not a valid array.', [
                'json' => $json,
            ]);
            return [];
        }

        $properties = [];

        foreach ($data as $propertyData) {
            if (empty($propertyData['name']) || empty($propertyData['type'])) {
                continue;
            }

            try {
                $properties[] = $this->map($propertyData);
            } catch (\ValueError $e) {
                $this->logger->warning('ToolPropertyMapper: skipping property with unknown type.', [
                    'property' => $propertyData,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return $properties;
    }
}
