<?php

namespace App\Entity\Post;

use App\Entity\Post\Thumbnail;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug',message:'Ce slug existe deja')]
class Post
{

    const STATES = ['STATE_DRAFT','STATE_PUBLISHED'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank()]
    private ?string $slug = null;

    #[ORM\Column(length: 255 , type: 'text')]
    #[Assert\NotBlank()]
    private ?string $content = null;

    #[ORM\Column(length: 255, )]
    private ?string $state = Post::STATES[0];

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\OneToOne(inversedBy: 'post', targetEntity: Thumbnail::class ,cascade:['persist', 'remove'])]
    private ?Thumbnail $thumbnail =null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'Post')]
    private Collection $categories;
   
    #[ORM\ManyToMany(targetEntity: Tags::class, mappedBy: 'Post')]
    private Collection $tags;

    public function __construct()
    {
        $this->updateAt = new \DateTimeImmutable();
        $this->createdAt = new \DateTimeImmutable();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        
    }
    #[ORM\PrePersist]
     public function prePersist()
     {
        $this->slug = (new Slugify())->slugify($this->title);
     }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updateAt = new \DateTimeImmutable();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getThumbnail(): ?Thumbnail
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?Thumbnail $thumbnail):self
    {
        $this->thumbnail=$thumbnail;
        return $this;
    }


    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addPost($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removePost($this);
        }

        return $this;
    }


      /**
     * @return Collection<int, tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTags(Tags $tags): self
    {
        if (!$this->tags->contains($tags)) {
            $this->tags->add($tags);
            $tags->addPost($this);
        }

        return $this;
    }

    public function removeTags(Tags $tags): self
    {
        if ($this->tags->removeElement($tags)) {
            $tags->removePost($this);
        }

        return $this;
    }
}
