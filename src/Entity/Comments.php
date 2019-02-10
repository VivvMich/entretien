<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentsRepository")
 */
class Comments
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
    private $contentComment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\News", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $news;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function __construct()
    {
        $this->create_at = new \DATETIME;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentComment(): ?string
    {
        return $this->contentComment;
    }

    public function setContentComment(string $contentComment): self
    {
        $this->contentComment = $contentComment;

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

    public function getNews(): ?News
    {
        return $this->news;
    }

    public function setNews(?News $news): self
    {
        $this->news = $news;

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
