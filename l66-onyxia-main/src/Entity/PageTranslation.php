<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: 'page_translations')]
#[ORM\Index(columns: ['locale', 'object_class', 'field', 'foreign_key'], name: 'page_translation_idx')]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class PageTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}