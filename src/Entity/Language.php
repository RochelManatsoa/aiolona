<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LanguageRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    use \App\Manager\Trait\LanguageTrait;

    const LEVEL_BASIC = 'BASIC';
    const LEVEL_CONVERSATIONNAL = 'CONVERSATIONNAL';
    const LEVEL_FLUENT = 'FLUENT';
    const LEVEL_NATIVE = 'NATIVE';
    const CHOICE_LEVEL = [
        'Basic' => self::LEVEL_BASIC,
        'Conversationnal' => self::LEVEL_CONVERSATIONNAL,
        'Fluent' => self::LEVEL_FLUENT,
        'Native or Bilingual' => self::LEVEL_NATIVE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['identity'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['identity'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['identity'])]
    private ?string $level = null;

    #[ORM\ManyToOne(inversedBy: 'languages', cascade: ['persist', 'remove'])]
    private ?Identity $identity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }

    public function setIdentity(?Identity $identity): static
    {
        $this->identity = $identity;

        return $this;
    }
}
