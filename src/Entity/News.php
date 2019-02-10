<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 */
class News
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $textContent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $commentsNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="news", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="news")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;


    public function __construct(){
        
        $this->create_at = new \DATETIME;
        $this->commentsNumber = 0;
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getCommentsNumber(): ?int
    {
        return $this->commentsNumber;
    }

    public function setCommentsNumber(int $commentsNumber): self
    {
        $this->commentsNumber = $commentsNumber;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setNews($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getNews() === $this) {
                $comment->setNews(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
