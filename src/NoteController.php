<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;


require_once("src/AbstractController.php");

class NoteController extends AbstractController
{

  public function createAction()
  {
      if (!empty($this->request->hasPost())) {
          $created = true;
          $noteData = [
              'title' => $this->request->postParam('title'),
              'description' => $this->request->postParam('description')
          ];
          $this->database->createNote($noteData);
          header('Location: /src/?before=created');
      }
      $this->view->render('create', $viewParams ?? []);
  }

  public function showAction()
  {
      $noteId = (int) $this->request->getParam('id');
      if (!$noteId) {
          header('Location: /src/?error=missingNoteId');
      }
      try {
          $viewParams = [
              'note' =>  $this->database->getNote($noteId),
          ];
      } catch (NotFoundException $e){
          header('Location: /src/?error=noteNotFound');
          exit;
      }
      $this->view->render('show', $viewParams ?? []);
  }

  public function listAction()
  {
      $viewParams = [
          'notes' =>  $this->database->getNotes(),
          'before' =>$this->request->getParam('before'),
          'error' =>$this->request->getParam('error')
      ];
      $this->view->render('list', $viewParams ?? []);
  }


}
