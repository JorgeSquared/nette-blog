<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;


final class HomepagePresenter extends Presenter
{
    public function __construct(private Explorer $db)
    {

    }

    public function renderDefault()
    {
        $this->template->posts = $this->db->table('post')
            ->order('created_at DESC')
            ->limit(5);
    }
}
