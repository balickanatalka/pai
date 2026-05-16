<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

#[ORM\Entity(repositoryClass: 'Gedmo\Loggable\Entity\Repository\LogEntryRepository')]
#[ORM\Table(name: 'ext_log_entries')]
class LogEntry extends AbstractLogEntry
{
    #[ORM\Column(type: 'json', nullable: true)]
    protected $data;
}
