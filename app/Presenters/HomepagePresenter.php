<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\PostManager;
use Nette\Application\UI\Presenter;
use Nette\Caching\Cache;
use Nette\Caching\Storage;


final class HomepagePresenter extends Presenter
{
    private Cache $cache;

    public function __construct(
        private PostManager $postManager,
        private Storage $storage
    ) {
        $this->cache = new Cache($storage, 'HomepagePresenter');
    }

    public function renderDefault()
    {
        $value = $this->cache->load('nazdar', function () {
            bdump('inside anonymous function');
            return 'čau';
        });

        bdump($value);

        $this->template->posts = $this->postManager->getPublicPosts(5);
    }
}
