<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Utils\DateTime;

class PostManager
{
    use SmartObject;

    public function __construct(private Explorer $db)
    {

    }

    public function getAll(): Selection
    {
        return $this->db->table('post');
    }

    public function getPublicPosts(): Selection
    {
        return $this->getAll()
            ->where('created_at < ', new DateTime)
            ->order('created_at DESC');
    }
}