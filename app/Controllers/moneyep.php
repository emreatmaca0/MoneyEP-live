<?php

namespace App\Controllers;
use App\Models\Users_Model;
use App\Models\Accounts_Model;
use App\Models\Debts_Model;

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
            return redirect()->to('dashboard');
        }
        else {
            return view('moneyep');
        }
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
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
                    return redirect()->to('dashboard');
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
            return redirect()->to('dashboard');
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
                    return redirect()->to('dashboard');
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
            return redirect()->to('login');
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
            return redirect()->to('login');
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


        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            $debtModel= new Debts_Model();
            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
            return view('mydebts', $data);
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return view('moneyep');
    }

    public function add_account()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
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

                    return redirect()->to('myassets');
                } else {
                    $accountModel= new Accounts_Model();
                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                    $data['validation_error'] = $validation->getErrors();
                    return view('myassets', $data);
                }
            }

        }
    }

    public function edit_account()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $accountModel = new Accounts_Model();
                $validation = \Config\Services::validation();

                $rules = [
                    'name' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[3]|max_length[30]',
                ];



                if ($this->validate($rules)) {
                    $clean_name = esc($this->request->getPost('name'));
                    $clean_id = esc($this->request->getPost('id'));
                    $accountData = [

                        'name' => $clean_name,
                    ];
                    $account = $accountModel->where('id', $clean_id)->first();
                    if ($account['user_id'] == $session->get('id')) {
                        $accountModel->update($clean_id, $accountData);
                    }

                    return redirect()->to('myassets');
                } else {
                    $data['validation_error'] = $validation->getErrors();
                    return view('myassets', $data);
                }
            }

        }
    }



    public function delete_account()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $accountModel = new Accounts_Model();
                $validation = \Config\Services::validation();


                $clean_id = esc($this->request->getPost('id'));
                $account = $accountModel->where('id', $clean_id)->first();
                if ($account['user_id'] == $session->get('id')) {
                    $accountModel->delete($clean_id);
                    return redirect()->to('myassets');
                }
                else
                {
                    return redirect()->to('myassets');
                }
            }

        }
    }

    public function add_debt()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $debtModel = new Debts_Model();
                $validation = \Config\Services::validation();

                $rules = [
                    'name' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[3]|max_length[30]',
                    'type' => 'required',
                    'currency' => 'required',
                    'date'=>'required',
                ];

                if ($this->validate($rules)) {
                    $clean_name = esc($this->request->getPost('name'));
                    $clean_type = esc($this->request->getPost('type'));
                    $clean_currency = esc($this->request->getPost('currency'));
                    $clean_date = esc($this->request->getPost('date'));
                    $debtData = [

                        'name' => $clean_name,
                        'type' => $clean_type,
                        'currency' => $clean_currency,
                        'date' => $clean_date,
                        'user_id' => $session->get('id')
                    ];
                    $debtModel->insert($debtData);

                    return redirect()->to('mydebts');
                } else {
                    $debtModel= new Debts_Model();
                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                    $data['validation_error'] = $validation->getErrors();
                    return view('mydebts', $data);
                }
            }

        }
    }

    public function edit_debt()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $debtModel = new Debts_Model();
                $validation = \Config\Services::validation();

                $rules = [
                    'name' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[3]|max_length[30]',
                    'date'=>'required',
                ];



                if ($this->validate($rules)) {
                    $clean_name = esc($this->request->getPost('name'));
                    $clean_id = esc($this->request->getPost('id'));
                    $clean_date = esc($this->request->getPost('date'));
                    $debtData = [

                        'name' => $clean_name,
                        'date' => $clean_date,
                    ];
                    $debt = $debtModel->where('id', $clean_id)->first();
                    if ($debt['user_id'] == $session->get('id')) {
                        $debtModel->update($clean_id, $debtData);
                    }

                    return redirect()->to('mydebts');
                } else {
                    $data['validation_error'] = $validation->getErrors();
                    return view('mydebts', $data);
                }
            }

        }
    }

    public function delete_debt()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $debtModel = new Debts_Model();
                $validation = \Config\Services::validation();


                $clean_id = esc($this->request->getPost('id'));
                $debt = $debtModel->where('id', $clean_id)->first();
                if ($debt['user_id'] == $session->get('id')) {
                    $debtModel->delete($clean_id);
                    return redirect()->to('mydebts');
                }
                else
                {
                    return redirect()->to('mydebts');
                }
            }

        }
    }
}
