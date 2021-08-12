<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;


require_once("src/controller/AbstractController.php");

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
          $this->redirect('/src/',['before'=>'created'] );
      }
      $this->view->render('create', $viewParams ?? []);
  }

  public function showAction()
  {
      $noteId = (int) $this->request->getParam('id');
      if (!$noteId) {
          $this->redirect('/src/',['error'=>'missingNoteId'] );

      }
      try {
          $viewParams = [
              'note' =>  $this->database->getNote($noteId),
          ];
      } catch (NotFoundException $e){
          $this->redirect('/src/',['error'=>'noteNotFound'] );

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

  public function editAction()
  {
      if ($this->request->isPost()) {
          $noteId = (int) $this->request->postParam('id');
          $noteData = [
              'title' => $this->request->postParam('title'),
              'description' => $this->request->postParam('description')
          ];
          $this->database->updateNote($noteId,$noteData);
          $this->redirect('/src/',['before'=>'updated'] );
          exit('ko');
      }

      $noteId = (int) $this->request->getParam('id');
      if (!$noteId) {
          $this->redirect('/src/',['error'=>'missingNoteId'] );

      }
      try {
          $viewParams = [
              'note' =>  $this->database->getNote($noteId),
          ];
      } catch (NotFoundException $e){
          $this->redirect('/src/',['error'=>'noteNotFound'] );
      }
      $this->view->render('edit', $viewParams ?? []);


  }

}
