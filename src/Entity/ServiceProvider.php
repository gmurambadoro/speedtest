<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ServiceProviderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceProviderRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     shortName="Internet-Service-Provider",
 * )
 */
class ServiceProvider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rating = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $loggedin = 0;

    /**
     * @ORM\Column(type="float")
     */
    private float $ispRating = 0;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $isp;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $longitude;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $latitude;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $ispulavg;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $ipAddress;

    public function __toString()
    {
        return (string)$this->getIsp();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getLoggedin(): ?int
    {
        return $this->loggedin;
    }

    public function setLoggedin(int $loggedin): self
    {
        $this->loggedin = $loggedin;

        return $this;
    }

    public function getIspRating(): ?float
    {
        return $this->ispRating;
    }

    public function setIspRating(float $ispRating): self
    {
        $this->ispRating = $ispRating;

        return $this;
    }

    public function getIsp(): ?string
    {
        return $this->isp;
    }

    public function setIsp(string $isp): self
    {
        $this->isp = $isp;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getIspulavg(): ?string
    {
        return $this->ispulavg;
    }

    public function setIspulavg(string $ispulavg): self
    {
        $this->ispulavg = $ispulavg;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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
