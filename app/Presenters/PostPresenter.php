<?php

declare(strict_types=1);


namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

class PostPresenter extends Presenter
{
    public function __construct(private Explorer $db)
    {

    }

    public function actionEdit(int $postId): void
    {
        $post = $this->db->table('post')->get($postId);

        if (!$post) {
            $this->error('Omlouváme se, ale příspěvek, který chcete zobrazit, nejspíš neexistuje', 404);
        }

        $this['postForm']->setDefaults($post->toArray());
    }

    public function renderShow(int $postId): void
    {
        $post = $this->db->table('post')->get($postId);

        if (!$post) {
            $this->error('Omlouváme se, ale příspěvek, který chcete zobrazit, nejspíš neexistuje', 404);
        }

        $this->template->post = $post;
        $this->template->comments = $post->related('comment')->order('created_at');
    }

    protected function createComponentCommentForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'E-mail:');

        $form->addTextArea('content', 'Komentář:')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }

    public function commentFormSucceeded(\stdClass $values): void
    {
        $postId = $this->getParameter('postId');

        $this->db->table('comment')->insert([
            'post_id' => $postId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titulek:')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    public function postFormSucceeded(Form $form, array $values): void
    {
        $postId = $this->getParameter('postId');

        if ($postId) {
            $post = $this->db->table('post')->get($postId);
            $post->update($values);
        } else {
            $post = $this->db->table('post')->insert($values);
        }

        $this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
        $this->redirect('Post:show', $post->id);
    }



}