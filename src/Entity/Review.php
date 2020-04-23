<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $createdOn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $updatedOn;


    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="reviews")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false)
     */
    protected $book;

    /**
     * Get book
     *
     * @return \App\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set book
     *
     * @param \App\Entity\Book $book
     * @return Review
     */
    public function setBook(\App\Entity\Book $book)
    {
        $this->book = $book;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

}
