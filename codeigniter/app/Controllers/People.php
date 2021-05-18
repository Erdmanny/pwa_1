<?php

namespace App\Controllers;

use App\Models\PeopleModel;

class People extends BaseController
{
    private $_peopleModel, $_session;

    public function __construct()
    {
        $this->_peopleModel = new PeopleModel();
        $this->_session = \Config\Services::session();
    }

    public function index()
    {
        $data['people'] = $this->_peopleModel->getPeople();
        echo view('header');
        echo view('people', $data);
        echo view('footer');
    }

    public function addPerson()
    {
        echo view('header');
        echo view('addPerson');
        echo view('footer');
    }

    public function addPerson_Validation()
    {
        helper(['form', 'url']);

        echo view('header');

        $error = $this->validate([
            'new-prename' => 'required',
            'new-surname' => 'required',
            'new-street' => 'required',
            'new-postcode' => 'required|min_length[5]|max_length[5]|numeric',
            'new-city' => 'required'
        ],
        [
            'new-prename' => [
                'required' => 'A prename is required'
            ],
            'new-surname' => [
                'required' => 'A surname is required'
            ],
            'new-streetname' => [
                'required' => 'A street is required'
            ],
            'new-postcode' => [
                'required' => 'A postcode is required',
                'min_length' => 'Postcode must be of length 5',
                'max_length' => 'Postcode must be of length 5',
                'numeric' => 'Postcode can only consist of numbers'
            ],
            'new-city' => [
                'required' => 'A city is required'
            ],
        ]);

        if (!$error) {
            echo view('addPerson', ['error' => $this->validator]);
        } else {
            $this->_peopleModel->addPerson(
                $this->request->getVar('new-prename'),
                $this->request->getVar('new-surname'),
                $this->request->getVar('new-street'),
                $this->request->getVar('new-plz'),
                $this->request->getVar('new-city'),
                $this->_session->get('token')
            );

            $this->_session->setFlashdata('success', 'Person added');

            return $this->response->redirect(site_url("people"));
        }

        echo view('footer');
    }

    function getSinglePerson($id = null)
    {
        $data['person'] = $this->_peopleModel->getSinglePerson($id);

        echo view('header');
        echo view("editPerson", $data);
        echo view('footer');
    }

    function editPerson_Validation()
    {
        helper(['form', 'url']);

        echo view('header');

        $error = $this->validate([
            'edit-prename' => 'required',
            'edit-surname' => 'required',
            'edit-street' => 'required',
            'edit-postcode' => 'required|min_length[5]|max_length[5]|numeric',
            'edit-city' => 'required'
        ],
            [
                'edit-prename' => [
                    'required' => 'A prename is required'
                ],
                'edit-surname' => [
                    'required' => 'A surname is required'
                ],
                'edit-streetname' => [
                    'required' => 'A street is required'
                ],
                'edit-postcode' => [
                    'required' => 'A postcode is required',
                    'min_length' => 'Postcode must be of length 5',
                    'max_length' => 'Postcode must be of length 5',
                    'numeric' => 'Postcode can only consist of numbers'
                ],
                'edit-city' => [
                    'required' => 'A city is required'
                ],
            ]);

        if (!$error) {
            $id = $this->request->getVar('id');
            $data['person'] = $this->_peopleModel->getSinglePerson($id);
            $data['error'] = $this->validator;
            echo view('editPerson', $data);
        } else {
            $this->_peopleModel->updatePerson(
                $this->request->getVar('id'),
                $this->request->getVar('edit-prename'),
                $this->request->getVar('edit-surname'),
                $this->request->getVar('edit-street'),
                $this->request->getVar('edit-postcode'),
                $this->request->getVar('edit-city'),
                $this->_session->get('token')
            );

            $this->_session->setFlashdata('success', 'Person updated');

            return $this->response->redirect(site_url("people"));
        }

        echo view('footer');
    }

    function deletePerson($id)
    {
        $this->_peopleModel->deletePerson($id);

        $this->_session->setFlashdata('success', 'Person deleted');

        return $this->response->redirect(site_url("people"));
    }

}
