<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordRepository")
 * @Assert\GroupSequence({"Word", "1", "2", "3", "4", "5"})
 */
class Word
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     * @Assert\NotBlank(message="Le champ est vide")
     * @Assert\Length(2, exactMessage="Le mot doit contenir exactement {{ limit }} kanjis", groups={"2"})
     * @AcmeAssert\ContainsKanji(groups={"1"})
     * @AcmeAssert\CheckPreviousEntry(groups={"3"})
     * @AcmeAssert\CheckPreviousKanji(groups={"4"})
     * @AcmeAssert\CheckJisho(groups={"5"})
     */
    private $word;

    /**
     * @var null|Shiritori
     * @ORM\ManyToOne(targetEntity="App\Entity\Shiritori", inversedBy="words")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shiritori;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $reading;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var null|array
     */
    private $senses = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getShiritori(): ?Shiritori
    {
        return $this->shiritori;
    }

    public function setShiritori(?Shiritori $shiritori): self
    {
        $this->shiritori = $shiritori;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReading(): ?string
    {
        return $this->reading;
    }

    public function setReading(string $reading): self
    {
        $this->reading = $reading;

        return $this;
    }

    public function getSenses(): ?array
    {
        return $this->senses;
    }

    public function setSenses(?array $senses): self
    {
        $this->senses = $senses;

        return $this;
    }
}
