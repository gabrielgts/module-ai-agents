<?php

namespace Gtstudio\AiAgents\Model\Data;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Magento\Framework\DataObject;

class AiToolsData extends DataObject implements AiToolsInterface
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
     * Getter for Properties.
     *
     * @return string|null
     */
    public function getProperties(): ?string
    {
        return $this->getData(self::PROPERTIES);
    }

    /**
     * Setter for Properties.
     *
     * @param string|null $properties
     *
     * @return void
     */
    public function setProperties(?string $properties): void
    {
        $this->setData(self::PROPERTIES, $properties);
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
}
