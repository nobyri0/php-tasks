<?php

namespace App\Entity;

use App\Repository\OrderFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderFileRepository::class)]
class OrderFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(length: 50)]
    private ?string $mimeType = null;

    #[ORM\Column]
    private ?int $fileSize = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $uploadedAt = null;

    #[ORM\ManyToOne(targetEntity: RestaurantOrder::class, inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RestaurantOrder $restaurantOrder = null;

    public function __construct()
    {
        $this->uploadedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): static
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function getRestaurantOrder(): ?RestaurantOrder
    {
        return $this->restaurantOrder;
    }

    public function setRestaurantOrder(?RestaurantOrder $restaurantOrder): static
    {
        $this->restaurantOrder = $restaurantOrder;
        return $this;
    }
}