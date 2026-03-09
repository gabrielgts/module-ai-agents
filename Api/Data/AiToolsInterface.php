<?php

namespace Gtstudio\AiAgents\Api\Data;

interface AiToolsInterface
{
    /**
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const CODE = "code";
    public const DESCRIPTION = "description";
    public const PROPERTIES = "properties";
    public const ADDITIONAL_CONFIGS = "additional_configs";

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void;

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * Setter for Code.
     *
     * @param string|null $code
     *
     * @return void
     */
    public function setCode(?string $code): void;

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Setter for Description.
     *
     * @param string|null $description
     *
     * @return void
     */
    public function setDescription(?string $description): void;

    /**
     * Getter for Properties.
     *
     * @return string|null
     */
    public function getProperties(): ?string;

    /**
     * Setter for Properties.
     *
     * @param string|null $properties
     *
     * @return void
     */
    public function setProperties(?string $properties): void;

    /**
     * Getter for AdditionalConfigs.
     *
     * @return string|null
     */
    public function getAdditionalConfigs(): ?string;

    /**
     * Setter for AdditionalConfigs.
     *
     * @param string|null $additionalConfigs
     *
     * @return void
     */
    public function setAdditionalConfigs(?string $additionalConfigs): void;
}
