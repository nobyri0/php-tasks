<?php

namespace App\Entity;

use App\Repository\RestaurantOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantOrderRepository::class)]
class RestaurantOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Выберите клиента')]
    private ?Client $client = null;

    #[ORM\ManyToMany(targetEntity: Dish::class)]
    #[Assert\Count(min: 1, minMessage: 'Выберите хотя бы одно блюдо')]
    private Collection $dishes;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $totalAmount = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'pending';

    #[ORM\OneToMany(
        mappedBy: 'restaurantOrder',
        targetEntity: OrderFile::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $files;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->orderDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(Dish $dish): static
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes->add($dish);
        }
        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function calculateTotal(): void
    {
        $total = 0;
        foreach ($this->dishes as $dish) {
            $total += (float) $dish->getPrice();
        }
        $this->totalAmount = (string) $total;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(OrderFile $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setRestaurantOrder($this);
        }
        return $this;
    }

    public function removeFile(OrderFile $file): static
    {
        if ($this->files->removeElement($file)) {
            if ($file->getRestaurantOrder() === $this) {
                $file->setRestaurantOrder(null);
            }
        }
        return $this;
    }
}