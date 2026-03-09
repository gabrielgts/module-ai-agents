<?php

namespace Gtstudio\AiAgents\Api\Data;

interface AiAgentInterface
{
    /**
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const CODE = "code";
    public const DESCRIPTION = "description";
    public const BACKGROUND = "background";
    public const STEPS = "steps";
    public const OUTPUT = "output";
    public const TOOLS = "tools";
    public const ADDITIONAL_CONFIGS = "additional_configs";
    public const CRON_ENABLED      = "cron_enabled";
    public const CRON_EXPRESSION   = "cron_expression";
    public const CRON_INPUT        = "cron_input";

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
     * Getter for Background.
     *
     * @return string|null
     */
    public function getBackground(): ?string;

    /**
     * Setter for Background.
     *
     * @param string|null $background
     *
     * @return void
     */
    public function setBackground(?string $background): void;

    /**
     * Getter for Steps.
     *
     * @return string|null
     */
    public function getSteps(): ?string;

    /**
     * Setter for Steps.
     *
     * @param string|null $steps
     *
     * @return void
     */
    public function setSteps(?string $steps): void;

    /**
     * Getter for Output.
     *
     * @return string|null
     */
    public function getOutput(): ?string;

    /**
     * Setter for Output.
     *
     * @param string|null $output
     *
     * @return void
     */
    public function setOutput(?string $output): void;

    /**
     * Getter for Tools.
     *
     * @return string|null
     */
    public function getTools(): ?string;

    /**
     * Setter for Tools.
     *
     * @param string|null $tools
     *
     * @return void
     */
    public function setTools(?string $tools): void;

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

    /**
     * @return bool
     */
    public function getCronEnabled(): bool;

    /**
     * @param bool $enabled
     * @return void
     */
    public function setCronEnabled(bool $enabled): void;

    /**
     * @return string|null
     */
    public function getCronExpression(): ?string;

    /**
     * @param string|null $expression
     * @return void
     */
    public function setCronExpression(?string $expression): void;

    /**
     * @return string|null
     */
    public function getCronInput(): ?string;

    /**
     * @param string|null $input
     * @return void
     */
    public function setCronInput(?string $input): void;
}
