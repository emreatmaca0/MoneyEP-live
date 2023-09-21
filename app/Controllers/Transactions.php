<?php

namespace App\Controllers;
use App\Models\Users_Model;
use App\Models\Accounts_Model;
use App\Models\Debts_Model;
use App\Models\Revenues_Model;
use CodeIgniter\HTTP\Request;
use DOMDocument;
use DOMXPath;

class Transactions extends BaseController
{
    function getDivContentByClass($url) {
        // Web sayfasını çekmek için cURL kullanabiliriz.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        // HTML içeriğini bir DOM nesnesine dönüştürelim.
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        // XPath kullanarak belirli bir class ismiyle hedef div'i bulalım.
        $xpath = new \DOMXPath($dom);
        $divs = $xpath->query("//*[contains(@class, 'YMlKec fxKbKc')]");

        // İlk bulunan div'in içeriğini alalım.
        if ($divs->length > 0) {
            return $divs->item(0)->textContent;
        } else {
            return "Could not find the div.";
        }
    }




    public function add_record()
    {
        $session = session();
        if(!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        }
        else
        {
            if ($this->request->getPost()) {
                $revenueModel = new Revenues_Model();
                $debtModel = new Debts_Model();
                $accountModel = new Accounts_Model();
                $validation = \Config\Services::validation();
                if ($this->request->getPost('type')=='revenue') {


                    if ($this->request->getPost('category') == 'debt') {
                        $rules = [
                            'date' => 'required',
                            'type' => 'required',
                            'currency' => 'required',
                            'amount' => 'required|regex_match[^([1-9]\d{0,8}|0)([,.]\d{1,2})?$]',
                            'category' => 'required',
                            'debt' => 'required|numeric',
                            'account' => 'required|numeric',
                            'description' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[2]|max_length[42]',

                        ];
                        if ($this->validate($rules)) {
                            $clean_date = esc($this->request->getPost('date'));
                            $clean_type = esc($this->request->getPost('type'));
                            $clean_currency = esc($this->request->getPost('currency'));
                            $clean_amount = esc($this->request->getPost('amount'));
                            $clean_amount=str_replace(",",".",$clean_amount);
                            $clean_category = esc($this->request->getPost('category'));
                            $clean_debt = esc($this->request->getPost('debt'));
                            $clean_account = esc($this->request->getPost('account'));
                            $clean_description = esc($this->request->getPost('description'));
                            $clean_dd=esc($this->request->getPost('dd'));
                            if (!empty($clean_dd))
                            {
                                $revenueData = [

                                    'date' => $clean_date,
                                    'type' => $clean_type,
                                    'currency' => $clean_currency,
                                    'amount' => $clean_amount,
                                    'category' => $clean_category,
                                    'debt' => $clean_debt,
                                    'account' => $clean_account,
                                    'description' => $clean_description,
                                    'dd' => $clean_dd,
                                    'user_id' => $session->get('id')
                                ];
                                $debt = $debtModel->where('id', $clean_debt)->first();
                                $account= $accountModel->where('id', $clean_account)->first();
                                if ($debt['user_id'] == $session->get('id')&&$account['user_id'] == $session->get('id'))
                                {
                                    $debtData = [
                                        'amount' => $debt['amount'] + $clean_amount
                                    ];
                                    $debtModel->update($clean_debt, $debtData);
                                    $accountData = [
                                        'amount' => $account['amount'] + $clean_amount
                                    ];
                                    $accountModel->update($clean_account, $accountData);
                                    $revenueModel->insert($revenueData);
                                    return redirect()->to('dashboard');
                                }
                                else
                                {
                                    $data['validation_error']['unauthorized_transaction'] = 'You are not authorized to make this transaction';
                                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                    return view('dashboard', $data);
                                }
                            }
                            else
                            {
                                $revenueData = [

                                    'date' => $clean_date,
                                    'type' => $clean_type,
                                    'currency' => $clean_currency,
                                    'amount' => $clean_amount,
                                    'category' => $clean_category,
                                    'debt' => $clean_debt,
                                    'account' => $clean_account,
                                    'description' => $clean_description,
                                    'user_id' => $session->get('id')
                                ];
                                $debt = $debtModel->where('id', $clean_debt)->first();
                                $account= $accountModel->where('id', $clean_account)->first();
                                if ($debt['user_id'] == $session->get('id')&&$account['user_id'] == $session->get('id'))
                                {
                                    $debtData = [
                                        'amount' => $debt['amount'] + $clean_amount
                                    ];
                                    $debtModel->update($clean_debt, $debtData);
                                    $accountData = [
                                        'amount' => $account['amount'] + $clean_amount
                                    ];
                                    $accountModel->update($clean_account, $accountData);
                                    $revenueModel->insert($revenueData);
                                    return redirect()->to('dashboard');
                                }
                                else
                                {
                                    $data['validation_error']['unauthorized_transaction'] = 'You are not authorized to make this transaction';
                                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                    return view('dashboard', $data);
                                }
                            }




                        } else {
                            $data['validation_error'] = $validation->getErrors();
                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                            return view('dashboard', $data);
                        }
                    }
                    else
                    {
                        $rules = [
                            'date' => 'required',
                            'type' => 'required',
                            'currency' => 'required',
                            'amount' => 'required|regex_match[^([1-9]\d{0,8}|0)([,.]\d{1,2})?$]',
                            'category' => 'required',
                            'account' => 'required|numeric',
                            'description' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[2]|max_length[42]',

                        ];
                        if ($this->validate($rules)) {
                            $clean_date = esc($this->request->getPost('date'));
                            $clean_type = esc($this->request->getPost('type'));
                            $clean_currency = esc($this->request->getPost('currency'));
                            $clean_amount = esc($this->request->getPost('amount'));
                            $clean_amount=str_replace(",",".",$clean_amount);
                            $clean_category = esc($this->request->getPost('category'));
                            $clean_account = esc($this->request->getPost('account'));
                            $clean_description = esc($this->request->getPost('description'));
                            $clean_dd=esc($this->request->getPost('dd'));
                            if (!empty($clean_dd))
                            {
                                $revenueData = [

                                    'date' => $clean_date,
                                    'type' => $clean_type,
                                    'currency' => $clean_currency,
                                    'amount' => $clean_amount,
                                    'category' => $clean_category,
                                    'account' => $clean_account,
                                    'description' => $clean_description,
                                    'dd' => $clean_dd,
                                    'user_id' => $session->get('id')
                                ];
                                $account= $accountModel->where('id', $clean_account)->first();
                                if ($account['user_id'] == $session->get('id'))
                                {

                                    $accountData = [
                                        'amount' => $account['amount'] + $clean_amount
                                    ];
                                    $accountModel->update($clean_account, $accountData);
                                    $revenueModel->insert($revenueData);
                                    return redirect()->to('dashboard');
                                }
                                else
                                {
                                    $data['validation_error']['unauthorized_transaction'] = 'You are not authorized to make this transaction';
                                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                    return view('dashboard', $data);
                                }
                            }
                            else
                            {
                                $revenueData = [

                                    'date' => $clean_date,
                                    'type' => $clean_type,
                                    'currency' => $clean_currency,
                                    'amount' => $clean_amount,
                                    'category' => $clean_category,
                                    'account' => $clean_account,
                                    'description' => $clean_description,
                                    'user_id' => $session->get('id')
                                ];
                                $account= $accountModel->where('id', $clean_account)->first();
                                if ($account['user_id'] == $session->get('id'))
                                {
                                    if ($account['currency']!=$clean_currency)
                                    {
                                        if ($account['currency']=='lira'&&$clean_currency=='dollar') {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='lira'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                    }
                                    else
                                    {


                                        $accountData = [
                                            'amount' => $account['amount'] + $clean_amount
                                        ];
                                        $accountModel->update($clean_account, $accountData);
                                        $revenueModel->insert($revenueData);
                                        return redirect()->to('dashboard');
                                    }
                                }
                                else
                                {
                                    $data['validation_error']['unauthorized_transaction'] = 'You are not authorized to make this transaction';
                                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                    return view('dashboard', $data);
                                }
                            }




                        } else {
                            $data['validation_error'] = $validation->getErrors();
                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                            return view('dashboard', $data);
                        }
                    }

                }
            }

        }
    }
}