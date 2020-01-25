<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordRepository")
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
     * @Assert\Length(2, exactMessage="Le mot doit faire exactement {{ limit }} kanjis")
     * @AcmeAssert\ContainsKanji()
     * @AcmeAssert\CheckPreviousEntry()
     * @AcmeAssert\CheckPreviousKanji()
     * @AcmeAssert\CheckJisho()
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
