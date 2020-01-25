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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ est vide")
     * @Assert\Length(2, exactMessage="Le mot doit faire exactement {{ limit }} kanjis", groups={"2"})
     * @AcmeAssert\ContainsKanji(groups={"1"})
     * @AcmeAssert\CheckPreviousEntry(groups={"3"})
     * @AcmeAssert\CheckPreviousKanji(groups={"4"})
     * @AcmeAssert\CheckJisho(groups={"5"})
     */
    private $word;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shiritori", inversedBy="words")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shiritori;

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
}
