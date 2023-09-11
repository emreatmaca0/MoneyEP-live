<?php

namespace App\Controllers;
use App\Models\Users_Model;
use App\Models\Accounts_Model;

class moneyep extends BaseController
{

    public function index()
    {
//        $usermodel= new Users_Model();
//        $data = [
//            'name' => 'moneyep',
//            'email' => 'eee',
//            'password' => '123'
//            ];
//        $usermodel->insert($data);
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/moneyep/dashboard');
        }
        else {
            return view('moneyep');
        }
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('moneyep/dashboard');
        }
        else {
            helper(['form', 'url']);
            $data = [];

            if ($this->request->getPost()) {
                $userModel = new Users_Model();

                $email = esc($this->request->getPost('email'));
                $password = esc($this->request->getPost('password'));

                $user = $userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    $session = session();
                    $session->set('id', $user['id']);
                    $session->set('name', $user['name']);
                    $session->set('email', $user['email']);
                    $session->set('isLoggedIn', true);
                    return redirect()->to('moneyep/dashboard');
                } else {
                    $data['login_error'] = 'Wrong email or password.';
                    return view('login', $data);
                }
            } else {
                return view('login');
            }
        }

    }

    public function signup()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/moneyep/dashboard');
        }
        else {
            helper(['form', 'url', 'security']);
            $data = [];

            if ($this->request->getPost()) {
                $userModel = new Users_Model();
                $validation = \Config\Services::validation();

                $rules = [
                    'name' => 'required|alpha_space|min_length[3]|max_length[30]',
                    'email' => 'required|valid_email|is_unique[Users.email]',
                    'password' => 'required|min_length[8]',
                ];

                if ($this->validate($rules) && $this->request->getPost('password') == $this->request->getPost('password_repeat')) {
                    $clean_name = esc($this->request->getPost('name'));
                    $clean_email = esc($this->request->getPost('email'));
                    $clean_password = esc($this->request->getPost('password'));
                    $userData = [
                        'name' => $clean_name,
                        'email' => $clean_email,
                        'password' => password_hash($clean_password, PASSWORD_DEFAULT),
                    ];
                    $userModel->insert($userData);
                    $session = session();
                    $session->set('name', $clean_name);
                    $session->set('email', $clean_email);
                    $session->set('isLoggedIn', true);
                    return redirect()->to('/moneyep/dashboard');
                } else {
                    $data['validation'] = $validation->getErrors();
                    if ($this->request->getPost('password') != $this->request->getPost('password_repeat')) {
                        $data['validation']['password_repeat'] = 'Passwords do not match';
                    }
                    return view('signup', $data);
                }
            } else {
                return view('signup');
            }
        }

    }



    public function dashboard()
    {
        $session = session();
        $user_name = $session->get('name');

        $data = [
            'user_name' => $user_name
        ];
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('moneyep/login');
        }
        else
        {
            return view('dashboard', $data);
        }

    }

    public function myassets()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('moneyep/login');
        }
        else
        {
            $accountModel= new Accounts_Model();
            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
            return view('myassets', $data);
        }
    }

    public function mydebts()
    {
        $session = session();
        $user_name = $session->get('name');

        $data = [
            'user_name' => $user_name
        ];
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('moneyep/login');
        }
        else
        {
            return view('mydebts', $data);
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('moneyep');
    }

    public function add_account()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('moneyep/login');
        }
        else
        {
            if ($this->request->getPost()) {
                $accountModel = new Accounts_Model();
                $validation = \Config\Services::validation();

                $rules = [
                    'name' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[3]|max_length[30]',
                    'type' => 'required',
                    'currency' => 'required'
                ];

                if ($this->validate($rules)) {
                    $clean_name = esc($this->request->getPost('name'));
                    $clean_type = esc($this->request->getPost('type'));
                    $clean_currency = esc($this->request->getPost('currency'));
                    $accountData = [

                        'name' => $clean_name,
                        'type' => $clean_type,
                        'currency' => $clean_currency,
                        'user_id' => $session->get('id')
                    ];
                    $accountModel->insert($accountData);

                    return redirect()->to('/moneyep/myassets');
                } else {
                    $data['validation_error'] = $validation->getErrors();
                    return view('myassets', $data);
                }
            }

        }
    }
}
