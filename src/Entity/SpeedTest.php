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
 *     shortName="Speed-Test",
 *     order={"timestamp"="DESC"}
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
    private ?Server $server;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    private int $bytesSent = 0;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $download;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $share;

    /**
     * @ORM\Column(type="integer")
     */
    private int $bytesReceived = 0;

    /**
     * @ORM\Column(type="float")
     */
    private float $ping = 0;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $upload;

    /**
     * @ORM\ManyToOne(targetEntity=ServiceProvider::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ServiceProvider $serviceProvider;

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
    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTimeInterface|null $timestamp
     * @return SpeedTest
     */
    public function setTimestamp(?\DateTimeInterface $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getBytesSent(): ?int
    {
        return $this->bytesSent ?? 0;
    }

    public function setBytesSent(int $bytesSent): self
    {
        $this->bytesSent = $bytesSent;

        return $this;
    }

    public function getDownload(): ?string
    {
        return $this->download;
    }

    public function setDownload(string $download): self
    {
        $this->download = $download;

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

    public function getBytesReceived(): int
    {
        return $this->bytesReceived ?? 0;
    }

    public function setBytesReceived(int $bytesReceived): self
    {
        $this->bytesReceived = $bytesReceived;

        return $this;
    }

    public function getPing(): ?float
    {
        return $this->ping;
    }

    public function setPing(float $ping): self
    {
        $this->ping = $ping;

        return $this;
    }

    public function getUpload(): ?string
    {
        return $this->upload;
    }

    public function setUpload(string $upload): self
    {
        $this->upload = $upload;

        return $this;
    }

    public function getServiceProvider(): ?ServiceProvider
    {
        return $this->serviceProvider;
    }

    public function setServiceProvider(?ServiceProvider $serviceProvider): self
    {
        $this->serviceProvider = $serviceProvider;

        return $this;
    }

}
