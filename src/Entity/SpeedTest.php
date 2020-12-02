<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SpeedTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpeedTestRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     shortName="Speed-Test"
 * )
 */
class SpeedTest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=15)
     */
    private $distance;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=15)
     */
    private $ping;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=15)
     */
    private $downloadSpeed;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=15)
     */
    private $uploadSpeed;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $share;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $ipAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     * @return SpeedTest
     */
    public function setTimestamp(?\DateTimeImmutable $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getPing(): ?string
    {
        return $this->ping;
    }

    public function setPing(string $ping): self
    {
        $this->ping = $ping;

        return $this;
    }

    public function getDownloadSpeed(): ?string
    {
        return $this->downloadSpeed;
    }

    public function setDownloadSpeed(string $downloadSpeed): self
    {
        $this->downloadSpeed = $downloadSpeed;

        return $this;
    }

    public function getUploadSpeed(): ?string
    {
        return $this->uploadSpeed;
    }

    public function setUploadSpeed(string $uploadSpeed): self
    {
        $this->uploadSpeed = $uploadSpeed;

        return $this;
    }

    public function getShare(): ?string
    {
        return $this->share;
    }

    public function setShare(?string $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }
}
