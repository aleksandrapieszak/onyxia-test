<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\PageRepository;
use App\Resolver\UpdateEntityTranslationResolver;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Loggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[Gedmo\Tree(type: 'nested')]
#[Gedmo\Loggable]
#[Gedmo\TranslationEntity(class: PageTranslation::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
#[ApiResource(
    paginationType: 'page',
    graphQlOperations: [
        new Query(
//            resolver: PageCollectionResolver::class,
//            args: ['uuid' => ['type' => 'String!']]
        ),
        new QueryCollection(
//            args: ['uuid' => ['type' => 'String!']]
        ),
        new DeleteMutation(name: 'delete'),
        new Mutation(name: 'create'),
        new Mutation(name: 'update'),
//        new Mutation(resolver: UpdateEntityTranslationResolver::class, args: [
//            'id' => ['type' => 'ID!'], 'title'=>['type'=>'String!']], name: 'updateCustom'),
    ]
)]
class Page implements Loggable
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ApiProperty(identifier: true)]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    #[Gedmo\Versioned]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Slug(fields: ['title'], unique: true, separator: '-')]
    #[Gedmo\Versioned]
    #[Gedmo\Translatable]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Translatable]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoKeywords = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seoThumb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $socialMediaTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $socialMediaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $socialMediaThumb = null;



    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[Gedmo\Blameable(on: 'create')]
    private ?User $createdBy = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    #[Gedmo\Blameable(on: 'update')]
    private ?User $updatedBy = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne]
    #[Gedmo\Blameable(on: 'change', field: 'publishedAt')]
    private ?User $publishedBy = null;

    #[ORM\Column(name: 'deletedAt', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $deletedAt;

    #[ORM\ManyToOne]
    #[Gedmo\Blameable(on: 'change', field: 'deletedAt')]
    private ?User $deletedBy;

    #[Gedmo\TreeLeft]
    #[ORM\Column(name: 'lft', type: Types::INTEGER)]
    private $lft;

    #[Gedmo\TreeLevel]
    #[ORM\Column(name: 'lvl', type: Types::INTEGER)]
    private $lvl;

    #[Gedmo\TreeRight]
    #[ORM\Column(name: 'rgt', type: Types::INTEGER)]
    private $rgt;

    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: Page::class)]
    #[ORM\JoinColumn(name: 'tree_root', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $root;

    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Page::class)]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    private $children;

    #[Gedmo\Locale]
    private $locale;

    /*
     *   //sofdeletable
     */

    public function __construct()
    {
        $uuid = Uuid::v7();
        $this->uuid = $uuid;
//        $stringUuid = $uuid->toRfc4122();
//        $this->uuid = $stringUuid;
    }

//    public function getId(): ?int
//    {
//        return $this->id;
//    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getThumb(): ?string
    {
        return $this->thumb;
    }

    public function setThumb(?string $thumb): static
    {
        $this->thumb = $thumb;

        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): static
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): static
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    public function getSeoKeywords(): ?string
    {
        return $this->seoKeywords;
    }

    public function setSeoKeywords(?string $seoKeywords): static
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    public function getSeoThumb(): ?string
    {
        return $this->seoThumb;
    }

    public function setSeoThumb(?string $seoThumb): static
    {
        $this->seoThumb = $seoThumb;

        return $this;
    }

    public function getSocialMediaTitle(): ?string
    {
        return $this->socialMediaTitle;
    }

    public function setSocialMediaTitle(?string $socialMediaTitle): static
    {
        $this->socialMediaTitle = $socialMediaTitle;

        return $this;
    }

    public function getSocialMediaDescription(): ?string
    {
        return $this->socialMediaDescription;
    }

    public function setSocialMediaDescription(?string $socialMediaDescription): static
    {
        $this->socialMediaDescription = $socialMediaDescription;

        return $this;
    }

    public function getSocialMediaThumb(): ?string
    {
        return $this->socialMediaThumb;
    }

    public function setSocialMediaThumb(?string $socialMediaThumb): static
    {
        $this->socialMediaThumb = $socialMediaThumb;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishedBy(): ?User
    {
        return $this->publishedBy;
    }

    public function setPublishedBy(?User $publishedBy): static
    {
        $this->publishedBy = $publishedBy;

        return $this;
    }

    /*
     * Musi być jako string, inaczej graphQL tego nie widzi
     */
    public function getUuid(): ?string
    {
        return $this->uuid->toRfc4122();
    }

//    public function setUuid(Uuid $uuid): Uuid
//    {
//        $this->uuid = $uuid;
//        //$this->uuid = $uuid->toRfc4122();
//        return $this->uuid;
//    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setParent(self $parent = null): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return User|null
     */
    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    /**
     * @param User|null $deletedBy
     */
    public function setDeletedBy(?User $deletedBy): void
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
