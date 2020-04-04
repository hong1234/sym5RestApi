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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $inserteddate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $updateddate;


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInserteddate()
    {
        return $this->inserteddate;
    }

    public function setInserteddate($inserteddate)
    {
        $this->inserteddate = $inserteddate;

        return $this;
    }

    public function getUpdateddate()
    {
        return $this->updateddate;
    }

    public function setUpdateddate($updateddate)
    {
        $this->updateddate = $updateddate;

        return $this;
    }

}
