<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     shortName="Server"
 * )
 */
class Server
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $sponsor;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private int $serverId = 0;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $serverName;

    public function __toString()
    {
        return (string)$this->getSponsor();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSponsor(): ?string
    {
        return $this->sponsor;
    }

    public function setSponsor(string $sponsor): self
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    public function getServerId(): ?int
    {
        return $this->serverId;
    }

    public function setServerId(int $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(string $serverName): self
    {
        $this->serverName = $serverName;

        return $this;
    }
}
