<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeoBaseRepository")
 */
class GeoBase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     *
     * @var int
     */
    private $longIp1;

    /**
     * @ORM\Column(type="bigint")
     *
     * @var int
     */
    private $longIp2;

    /**
     * @ORM\Column(type="string", length=16)
     *
     * @var string
     */
    private $ip1;

    /**
     * @ORM\Column(type="string", length=16)
     *
     * @var string
     */
    private $ip2;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $countryCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $city_id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getLongIp1(): ?int
    {
        return $this->longIp1;
    }

    /**
     * @param int $longIp1
     * 
     * @return self
     */
    public function setLongIp1(int $longIp1): self
    {
        $this->longIp1 = $longIp1;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLongIp2(): ?int
    {
        return $this->longIp2;
    }

    /**
     * @param int $longIp2
     * 
     * @return self
     */
    public function setLongIp2(int $longIp2): self
    {
        $this->longIp2 = $longIp2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp1(): ?string
    {
        return $this->ip1;
    }

    /**
     * @param string $ip1
     * 
     * @return self
     */
    public function setIp1(string $ip1): self
    {
        $this->ip1 = $ip1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp2(): ?string
    {
        return $this->ip2;
    }

    /**
     * @param string $ip2
     * 
     * @return self
     */
    public function setIp2(string $ip2): self
    {
        $this->ip2 = $ip2;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * 
     * @return self
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCityId(): ?int
    {
        return $this->city_id;
    }

    /**
     * @param int $city_id
     * 
     * @return self
     */
    public function setCityId(int $city_id): self
    {
        $this->city_id = $city_id;

        return $this;
    }
}
