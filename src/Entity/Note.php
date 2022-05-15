<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\ManyToOne(targetEntity: Partner::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private $partner_id;

    #[ORM\Column(type: 'integer')]
    private $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPartnerId(): ?Partner
    {
        return $this->partner_id;
    }

    public function setPartnerId(?Partner $partner_id): self
    {
        $this->partner_id = $partner_id;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
