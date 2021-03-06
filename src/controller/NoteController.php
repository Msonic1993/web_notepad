<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;

require_once('src/controller/AbstractController.php');

class NoteController extends AbstractController
{
    public function createAction(): void
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->database->create($noteData);
            $this->redirect('/src/', ['before' => 'created']);
        }

        $this->view->render('create');
    }

    public function showAction(): void
    {
        $this->view->render(
            'show',
            ['note' => $this->getNote()]
        );
    }

    public function listAction(): void
    {
        $sortBy = $this->request->getParam('sortby', 'title');
        $sortOrder = $this->request->getParam('sortorder', 'desc');

        $this->view->render(
            'list',
            [
                'sort' => ['by' => $sortBy, 'order' => $sortOrder],
                'notes' => $this->database->list($sortBy, $sortOrder),
                'before' => $this->request->getParam('before'),
                'error' => $this->request->getParam('error')
            ]
        );
    }

    public function editAction(): void
    {

        if ($this->request->isPost()) {
            $noteId = (int)$this->request->postParam('id');
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->database->edit($noteId, $noteData);
            $this->redirect('/src/', ['before' => 'edited']);
        }

        $this->view->render(
            'edit',
            ['note' => $this->getNote()]
        );
    }

    public function deleteAction(): void
    {
        if ($this->request->isPost()) {
            $id = (int)$this->request->postParam('id');
            $this->database->delete($id);
            $this->redirect('/src/', ['before' => 'deleted']);
        }

        $this->view->render(
            'delete',
            ['note' => $this->getNote()]
        );
    }

    final private function getNote(): array
    {
        $noteId = (int)$this->request->getParam('id');
        if (!$noteId) {
            $this->redirect('/src/', ['error' => 'missingNoteId']);
        }

        try {
            $note = $this->database->get($noteId);
        } catch (NotFoundException $e) {
            $this->redirect('/src/', ['error' => 'noteNotFound']);
        }

        return $note;
    }
}
