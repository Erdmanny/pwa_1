<?php namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Model;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class User extends BaseController
{
    private $_userModel, $_session;

    public function __construct()
    {
        $this->_userModel = new UserModel();
        $this->_session = \Config\Services::session();
    }


    public function index()
    {
        helper(['form', 'url']);
        echo view('header');

        if ($this->request->getMethod() == 'post') {
            $error = $this->validate([
                'email' => 'required|valid_email',
                'password' => 'required|validateUser[email,password]',
            ],
                [
                    'email' => [
                        'required' => 'A valid email is required',
                        'valid_email' => 'A valid email is required'
                    ],
                    'password' => [
                        'required' => 'A password is required',
                        'validateUser' => 'Email or Password don\'t match'
                    ]
                ]
            );

            if (!$error) {
                echo view('login', ['error' => $this->validator]);
            } else {

                $user = $this->_userModel->validatePassword(
                    $this->request->getVar('email'),
                    $this->request->getVar('password')
                );

                $this->_session->set([
                    'id' => $user->id,
                    'email' => $user->email,
                    'isLoggedIn' => true,
                    'token' => $user->token
                ]);

                return $this->response->redirect(site_url("people"));
            }
        } else {
            echo view('login');
        }

        echo view('footer');
    }



    public function register()
    {
        helper(['form', 'url']);

        echo view('header');

        if ($this->request->getMethod() == 'post') {
            $error = $this->validate([
                'email' => 'required|max_length[50]|valid_email|is_unique[user.email]',
                'token' => 'required|min_length[4]|max_length[4]|is_unique[user.token]',
                'password' => 'required|min_length[8]|max_length[255]',
                'password_confirm' => 'matches[password]'
            ],
                [
                    'email' => [
                        'required' => 'A valid email is required',
                        'max_length' => 'Email can\'t be longter than 50',
                        'valid_email' => 'A valid email is required',
                        'is_unique' => 'Email does already exist'
                    ],
                    'token' => [
                        'required' => 'A token is required',
                        'min_length' => 'Token must be of length 4',
                        'max_length' => 'Token must be of length 4',
                        'is_unique' => 'Token does already exist'
                    ],
                    'password' => [
                        'required' => 'A password is required',
                        'min_length' => 'Password must have more than 8 signs',
                        'max_length' => 'Password must have less than 255 signs'
                    ],
                    'password_confirm' => [
                        'matches' => 'Passwords don\'t match'
                    ]
                ]);

            if (!$error) {
                echo view('register', ['error' => $this->validator]);
            } else {
                $this->_userModel->createUser(
                    $this->request->getVar('email'),
                    $this->request->getVar('password'),
                    $this->request->getVar('token')
                );

                $this->_session->setFlashdata('success', 'Successful Registration');
                return redirect()->to('/');
            }
        } else {
            echo view('register');
        }

        echo view('footer');
    }


    public function logout()
    {
        $this->_session->destroy();
        return redirect()->to('/');
    }

}