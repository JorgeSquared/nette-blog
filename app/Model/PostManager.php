<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
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

    public function getById(int $id): ?ActiveRow
    {
        $id = (int) $id;
        return $this->getAll()->get($id);
    }

    public function insert(array $values): ActiveRow
    {
        return $this->getAll()->insert($values);
    }

    public function getPublicPosts(int $limit = null): Selection
    {
        $retVal = $this->getAll()
            ->where('created_at < ', new DateTime)
            ->order('created_at DESC');

        if ($limit) {
            $retVal->limit($limit);
        }

        return $retVal;
    }
}