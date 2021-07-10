<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @ORM\EntityListeners({"App\EntityListener\TrickListener"})
 */
class Trick
{
    public const LIMIT_PER_PAGE = 9;

    /**
     * @Groups("trick")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups("trick")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "La description ne peut pas dépasser {{ limit }} caractères."
     * )
     */
    private ?string $name;

    /**
     * @Groups("trick")
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Length(
     *     min = 50,
     *     max = 255,
     *     minMessage = "La description ne peut pas faire moins de {{ limit }} caractères.",
     *     maxMessage = "La description ne peut pas dépasser {{ limit }} caractères."
     * )
     */
    private ?string $description;

    /**
     * @Groups("trick")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     */
    private ?string $label;

    /**
     * @Groups("trick")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $coverImage = "";

    /**
     * @Groups("trick")
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     */
    private Collection  $pictures;

    /**
     * @Groups("trick")
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private Collection  $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $comments;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(?Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(?Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}
