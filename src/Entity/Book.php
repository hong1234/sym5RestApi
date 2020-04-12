<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
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
     * @ORM\Column(type="integer")
     */
    private $price;

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
     * @ORM\OneToMany(targetEntity="Review", mappedBy="book", cascade={"persist"})
     */
    protected $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        
    }

    /**
     * Add review
     *
     * @param \App\Entity\Review $review
     * @return Book
     */
    public function addReview(\App\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \App\Entity\Review $review
     */
    public function removeReview(\App\Entity\Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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
