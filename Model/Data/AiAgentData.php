<?php

namespace Gtstudio\AiAgents\Model\Data;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Framework\DataObject;

class AiAgentData extends DataObject implements AiAgentInterface
{
    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Getter for Code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->getData(self::CODE);
    }

    /**
     * Setter for Code.
     *
     * @param string|null $code
     *
     * @return void
     */
    public function setCode(?string $code): void
    {
        $this->setData(self::CODE, $code);
    }

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Setter for Description.
     *
     * @param string|null $description
     *
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Getter for Background.
     *
     * @return string|null
     */
    public function getBackground(): ?string
    {
        return $this->getData(self::BACKGROUND);
    }

    /**
     * Setter for Background.
     *
     * @param string|null $background
     *
     * @return void
     */
    public function setBackground(?string $background): void
    {
        $this->setData(self::BACKGROUND, $background);
    }

    /**
     * Getter for Steps.
     *
     * @return string|null
     */
    public function getSteps(): ?string
    {
        return $this->getData(self::STEPS);
    }

    /**
     * Setter for Steps.
     *
     * @param string|null $steps
     *
     * @return void
     */
    public function setSteps(?string $steps): void
    {
        $this->setData(self::STEPS, $steps);
    }

    /**
     * Getter for Output.
     *
     * @return string|null
     */
    public function getOutput(): ?string
    {
        return $this->getData(self::OUTPUT);
    }

    /**
     * Setter for Output.
     *
     * @param string|null $output
     *
     * @return void
     */
    public function setOutput(?string $output): void
    {
        $this->setData(self::OUTPUT, $output);
    }

    /**
     * Getter for Tools.
     *
     * @return string|null
     */
    public function getTools(): ?string
    {
        return $this->getData(self::TOOLS);
    }

    /**
     * Setter for Tools.
     *
     * @param string|null $tools
     *
     * @return void
     */
    public function setTools(?string $tools): void
    {
        $this->setData(self::TOOLS, $tools);
    }

    /**
     * Getter for AdditionalConfigs.
     *
     * @return string|null
     */
    public function getAdditionalConfigs(): ?string
    {
        return $this->getData(self::ADDITIONAL_CONFIGS);
    }

    /**
     * Setter for AdditionalConfigs.
     *
     * @param string|null $additionalConfigs
     *
     * @return void
     */
    public function setAdditionalConfigs(?string $additionalConfigs): void
    {
        $this->setData(self::ADDITIONAL_CONFIGS, $additionalConfigs);
    }

    public function getCronEnabled(): bool
    {
        return (bool) $this->getData(self::CRON_ENABLED);
    }

    public function setCronEnabled(bool $enabled): void
    {
        $this->setData(self::CRON_ENABLED, (int) $enabled);
    }

    public function getCronExpression(): ?string
    {
        return $this->getData(self::CRON_EXPRESSION);
    }

    public function setCronExpression(?string $expression): void
    {
        $this->setData(self::CRON_EXPRESSION, $expression);
    }

    public function getCronInput(): ?string
    {
        return $this->getData(self::CRON_INPUT);
    }

    public function setCronInput(?string $input): void
    {
        $this->setData(self::CRON_INPUT, $input);
    }
}
