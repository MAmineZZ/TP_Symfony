<?php

namespace App\Entity;

use App\Repository\NationalteamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NationalteamRepository::class)]
class Nationalteam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $Name = null;



    #[ORM\OneToMany(mappedBy: 'NationalTeam', targetEntity: Player::class)]
    private Collection $Player;

    #[ORM\Column(length: 150)]
    private ?string $Flag = null;

    public function __construct()
    {
        $this->Player = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }



    /**
     * @return Collection<int, Player>
     */
    public function getPlayer(): Collection
    {
        return $this->Player;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->Player->contains($player)) {
            $this->Player->add($player);
            $player->setNationalTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->Player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getNationalTeam() === $this) {
                $player->setNationalTeam(null);
            }
        }

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->Flag;
    }

    public function setFlag(string $Flag): self
    {
        $this->Flag = $Flag;

        return $this;
    }
}
