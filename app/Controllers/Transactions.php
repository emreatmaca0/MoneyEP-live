<?php

namespace App\Controllers;
use App\Models\Users_Model;
use App\Models\Accounts_Model;
use App\Models\Debts_Model;
use App\Models\Revenues_Model;
use App\Models\Expenses_Model;
use App\Models\Remittances_Model;
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
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
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] + $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            $shortcurrency='TRY';
                                            switch ($clean_currency)
                                            {
                                                case 'lira':
                                                    $shortcurrency='TRY';
                                                    break;
                                                case 'dollar':
                                                    $shortcurrency='USD';
                                                    break;
                                                case 'euro':
                                                    $shortcurrency='EUR';
                                                    break;
                                            }
                                            if ($debt['currency']=='lira')
                                            {

                                                if ($shortcurrency=='TRY')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }

                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                if ($shortcurrency=='USD')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            else
                                            {
                                                if ($shortcurrency=='EUR')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
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
                                        $shortcurrency='TRY';
                                        switch ($clean_currency)
                                        {
                                            case 'lira':
                                                $shortcurrency='TRY';
                                                break;
                                            case 'dollar':
                                                $shortcurrency='USD';
                                                break;
                                            case 'euro':
                                                $shortcurrency='EUR';
                                                break;
                                        }
                                        if ($debt['currency']=='lira')
                                        {

                                            if ($shortcurrency=='TRY')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }

                                        }
                                        elseif ($debt['currency']=='dollar')
                                        {
                                            if ($shortcurrency=='USD')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
                                        else
                                        {
                                            if ($shortcurrency=='EUR')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
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
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
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
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] + $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            $shortcurrency='TRY';
                                            switch ($clean_currency)
                                            {
                                                case 'lira':
                                                    $shortcurrency='TRY';
                                                    break;
                                                case 'dollar':
                                                    $shortcurrency='USD';
                                                    break;
                                                case 'euro':
                                                    $shortcurrency='EUR';
                                                    break;
                                            }
                                            if ($debt['currency']=='lira')
                                            {

                                                if ($shortcurrency=='TRY')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }

                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                if ($shortcurrency=='USD')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            else
                                            {
                                                if ($shortcurrency=='EUR')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] + $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
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
                                        $shortcurrency='TRY';
                                        switch ($clean_currency)
                                        {
                                            case 'lira':
                                                $shortcurrency='TRY';
                                                break;
                                            case 'dollar':
                                                $shortcurrency='USD';
                                                break;
                                            case 'euro':
                                                $shortcurrency='EUR';
                                                break;
                                        }
                                        if ($debt['currency']=='lira')
                                        {

                                            if ($shortcurrency=='TRY')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }

                                        }
                                        elseif ($debt['currency']=='dollar')
                                        {
                                            if ($shortcurrency=='USD')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
                                        else
                                        {
                                            if ($shortcurrency=='EUR')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] + $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
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
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
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
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
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
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
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
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
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
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
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
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] + $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
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







                elseif ($this->request->getPost('type')=='expense')
                {
                    $revenueModel = new Expenses_Model();
                    if ($this->request->getPost('category') == 'debt-payment') {
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
                                if ($debt['user_id'] == $session->get('id')&&$account['user_id'] == $session->get('id')&&$account['amount']>=$clean_amount&&$debt['amount']>=$clean_amount)
                                {
                                    if ($account['currency']!=$clean_currency)
                                    {
                                        if ($account['currency']=='lira'&&$clean_currency=='dollar') {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='lira'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] - $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            $shortcurrency='TRY';
                                            switch ($clean_currency)
                                            {
                                                case 'lira':
                                                    $shortcurrency='TRY';
                                                    break;
                                                case 'dollar':
                                                    $shortcurrency='USD';
                                                    break;
                                                case 'euro':
                                                    $shortcurrency='EUR';
                                                    break;
                                            }
                                            if ($debt['currency']=='lira')
                                            {

                                                if ($shortcurrency=='TRY')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }

                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                if ($shortcurrency=='USD')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            else
                                            {
                                                if ($shortcurrency=='EUR')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                    }
                                    else
                                    {


                                        $accountData = [
                                            'amount' => $account['amount'] - $clean_amount
                                        ];
                                        $accountModel->update($clean_account, $accountData);
                                        $revenueModel->insert($revenueData);
                                        $shortcurrency='TRY';
                                        switch ($clean_currency)
                                        {
                                            case 'lira':
                                                $shortcurrency='TRY';
                                                break;
                                            case 'dollar':
                                                $shortcurrency='USD';
                                                break;
                                            case 'euro':
                                                $shortcurrency='EUR';
                                                break;
                                        }
                                        if ($debt['currency']=='lira')
                                        {

                                            if ($shortcurrency=='TRY')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }

                                        }
                                        elseif ($debt['currency']=='dollar')
                                        {
                                            if ($shortcurrency=='USD')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
                                        else
                                        {
                                            if ($shortcurrency=='EUR')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
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
                                if ($debt['user_id'] == $session->get('id')&&$account['user_id'] == $session->get('id')&&$account['amount']>=$clean_amount&&$debt['amount']>=$clean_amount)
                                {


                                    if ($account['currency']!=$clean_currency)
                                    {
                                        if ($account['currency']=='lira'&&$clean_currency=='dollar') {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='lira'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='euro')
                                            {
                                                $url = 'https://www.google.com/finance/quote/USD-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            if ($debt['currency']=='lira')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                $url = 'https://www.google.com/finance/quote/EUR-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] - $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            $shortcurrency='TRY';
                                            switch ($clean_currency)
                                            {
                                                case 'lira':
                                                    $shortcurrency='TRY';
                                                    break;
                                                case 'dollar':
                                                    $shortcurrency='USD';
                                                    break;
                                                case 'euro':
                                                    $shortcurrency='EUR';
                                                    break;
                                            }
                                            if ($debt['currency']=='lira')
                                            {

                                                if ($shortcurrency=='TRY')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }

                                            }
                                            elseif ($debt['currency']=='dollar')
                                            {
                                                if ($shortcurrency=='USD')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            else
                                            {
                                                if ($shortcurrency=='EUR')
                                                {
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $clean_amount
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                                else
                                                {
                                                    $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                    $result = $this->getDivContentByClass($url);
                                                    $result = $clean_amount * $result;
                                                    $result = round($result, 2);
                                                    $debtData = [
                                                        'amount' => $debt['amount'] - $result
                                                    ];
                                                    $debtModel->update($clean_debt, $debtData);
                                                }
                                            }
                                            return redirect()->to('dashboard');
                                        }
                                    }
                                    else
                                    {


                                        $accountData = [
                                            'amount' => $account['amount'] - $clean_amount
                                        ];
                                        $accountModel->update($clean_account, $accountData);
                                        $revenueModel->insert($revenueData);
                                        $shortcurrency='TRY';
                                        switch ($clean_currency)
                                        {
                                            case 'lira':
                                                $shortcurrency='TRY';
                                                break;
                                            case 'dollar':
                                                $shortcurrency='USD';
                                                break;
                                            case 'euro':
                                                $shortcurrency='EUR';
                                                break;
                                        }
                                        if ($debt['currency']=='lira')
                                        {

                                            if ($shortcurrency=='TRY')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-TRY';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }

                                        }
                                        elseif ($debt['currency']=='dollar')
                                        {
                                            if ($shortcurrency=='USD')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-USD';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
                                        else
                                        {
                                            if ($shortcurrency=='EUR')
                                            {
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $clean_amount
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                            else
                                            {
                                                $url = 'https://www.google.com/finance/quote/'.$shortcurrency.'-EUR';
                                                $result = $this->getDivContentByClass($url);
                                                $result = $clean_amount * $result;
                                                $result = round($result, 2);
                                                $debtData = [
                                                    'amount' => $debt['amount'] - $result
                                                ];
                                                $debtModel->update($clean_debt, $debtData);
                                            }
                                        }
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
                                if ($account['user_id'] == $session->get('id')&&$account['amount']>=$clean_amount)
                                {

                                    if ($account['currency']!=$clean_currency)
                                    {
                                        if ($account['currency']=='lira'&&$clean_currency=='dollar') {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] - $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                    }
                                    else
                                    {


                                        $accountData = [
                                            'amount' => $account['amount'] - $clean_amount
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
                                if ($account['user_id'] == $session->get('id')&&$account['amount']>=$clean_amount)
                                {
                                    if ($account['currency']!=$clean_currency)
                                    {
                                        if ($account['currency']=='lira'&&$clean_currency=='dollar') {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
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
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');

                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum'&&$clean_currency=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData = [
                                                'amount' => $account['amount'] - $result
                                            ];
                                            $accountModel->update($clean_account, $accountData);

                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $accountData = [
                                                'amount' => $account['amount'] - $clean_amount
                                            ];
                                            $accountModel->update($clean_account, $accountData);
                                            $revenueModel->insert($revenueData);
                                            return redirect()->to('dashboard');
                                        }
                                    }
                                    else
                                    {


                                        $accountData = [
                                            'amount' => $account['amount'] - $clean_amount
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
                elseif ($this->request->getPost('type')=='remittance')
                {
                    $remittanceModel=new Remittances_Model();
                    $rules = [
                        'date' => 'required',
                        'type' => 'required',
                        'currency' => 'required',
                        'amount' => 'required|regex_match[^([1-9]\d{0,8}|0)([,.]\d{1,2})?$]',
                        'commission_amount' => 'required|regex_match[^([1-9]\d{0,8}|0)([,.]\d{1,2})?$]',
                        'source' => 'required|numeric',
                        'account' => 'required|numeric',
                        'description' => 'required|regex_match[^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]*$]|min_length[2]|max_length[42]',

                    ];
                    if ($this->validate($rules))
                    {
                        $clean_date = esc($this->request->getPost('date'));
                        $clean_type = esc($this->request->getPost('type'));
                        $clean_currency = esc($this->request->getPost('currency'));
                        $clean_amount = esc($this->request->getPost('amount'));
                        $clean_amount=str_replace(",",".",$clean_amount);
                        $clean_commission = esc($this->request->getPost('commission_amount'));
                        $clean_commission=str_replace(",",".",$clean_commission);
                        $clean_source = esc($this->request->getPost('source'));
                        $clean_account = esc($this->request->getPost('account'));
                        $clean_description = esc($this->request->getPost('description'));
                        $clean_dd=esc($this->request->getPost('dd'));
                        $remittanceData='';
                        if (!empty($clean_dd))
                        {
                            $remittanceData = [

                                'date' => $clean_date,
                                'type' => $clean_type,
                                'currency' => $clean_currency,
                                'amount' => $clean_amount,
                                'commission' => $clean_commission,
                                'source_account' => $clean_source,
                                'account' => $clean_account,
                                'description' => $clean_description,
                                'dd' => $clean_dd,
                                'user_id' => $session->get('id')
                            ];
                        }
                        else
                        {
                            $remittanceData = [

                                'date' => $clean_date,
                                'type' => $clean_type,
                                'currency' => $clean_currency,
                                'amount' => $clean_amount,
                                'commission' => $clean_commission,
                                'source_account' => $clean_source,
                                'account' => $clean_account,
                                'description' => $clean_description,
                                'user_id' => $session->get('id')
                            ];
                        }
                        $source= $accountModel->where('id', $clean_source)->first();
                        $account= $accountModel->where('id', $clean_account)->first();
                        if ($account['user_id']==$session->get('id')&&$source['user_id']==$session->get('id'))
                        {
                            if($account['id']!=$source['id'])
                            {
                                $is_source='';
                                $is_currency='';
                                $is_biggest=false;
                                switch ($source['currency'])
                                    {
                                        case 'lira':
                                            $is_source='TRY';
                                            break;
                                        case 'dollar':
                                            $is_source='USD';
                                            break;
                                        case 'euro':
                                            $is_source='EUR';
                                            break;
                                            case 'bitcoin':
                                            $is_source='BTC';
                                            break;
                                            case 'ethereum':
                                            $is_source='ETH';
                                            break;
                                            case 'tether':
                                            $is_source='USD';
                                            break;
                                    }
                                    switch ($clean_currency)
                                    {
                                        case 'lira':
                                            $is_currency='TRY';
                                            break;
                                        case 'dollar':
                                            $is_currency='USD';
                                            break;
                                        case 'euro':
                                            $is_currency='EUR';
                                            break;
                                    }
                                    if($is_source==$is_currency)
                                    {
                                        if($source['amount']>=$clean_amount+$clean_commission)
                                        {
                                            $is_biggest=true;
                                        }
                                    }
                                    else{
                                        $url = 'https://www.google.com/finance/quote/'.$is_currency.'-'.$is_source;
                                        $result = $this->getDivContentByClass($url);
                                        $result = $clean_amount * $result;
                                        $commission_result=$clean_commission*$result;
                                        if($source['amount']>=$result+$commission_result)
                                        {
                                            $is_biggest=true;
                                        }
                                    }

                                if($is_biggest)
                                {
                                    if ($source['currency']=='lira'&&$clean_currency=='lira') {

                                        if($clean_commission>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$clean_commission,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $sourceData = [
                                            'amount' => $source['amount'] - $clean_amount-$clean_commission
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }

                                    }
                                    elseif ($source['currency']=='lira'&&$clean_currency=='dollar') {
                                        $url = 'https://www.google.com/finance/quote/USD-TRY';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }

                                    }
                                    elseif ($source['currency']=='lira'&&$clean_currency=='euro')
                                    {
                                        $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='dollar'||$source['currency']=='tether'&&$clean_currency=='dollar')
                                    {

                                        if($clean_commission>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$clean_commission,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $sourceData = [
                                            'amount' => $source['amount'] - $clean_amount-$clean_commission
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='dollar'||$source['currency']=='tether'&&$clean_currency=='lira')
                                    {
                                        $url = 'https://www.google.com/finance/quote/TRY-USD';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='dollar'||$source['currency']=='tether'&&$clean_currency=='euro')
                                    {
                                        $url = 'https://www.google.com/finance/quote/EUR-USD';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='euro'&&$clean_currency=='euro')
                                    {

                                        if($clean_commission>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$clean_commission,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }

                                        $sourceData = [
                                            'amount' => $source['amount'] - $clean_amount-$clean_commission
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='euro'&&$clean_currency=='lira')
                                    {
                                        $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='euro'&&$clean_currency=='dollar')
                                    {
                                        $url = 'https://www.google.com/finance/quote/USD-EUR';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        $commission_result=round($commission_result,2);
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $result = round($result, 2);
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='bitcoin'&&$clean_currency=='lira')
                                    {
                                        $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='bitcoin'&&$clean_currency=='dollar')
                                    {
                                        $url = 'https://www.google.com/finance/quote/USD-BTC';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='bitcoin'&&$clean_currency=='euro')
                                    {
                                        $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='ethereum'&&$clean_currency=='lira')
                                    {
                                        $url = 'https://www.google.com/finance/quote/TRY-ETH';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $url = 'https://www.google.com/finance/quote/TRY-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='ethereum'&&$clean_currency=='dollar')
                                    {
                                        $url = 'https://www.google.com/finance/quote/USD-ETH';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-EUR';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $url = 'https://www.google.com/finance/quote/USD-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                    elseif ($source['currency']=='ethereum'&&$clean_currency=='euro')
                                    {
                                        $url = 'https://www.google.com/finance/quote/EUR-ETH';
                                        $result = $this->getDivContentByClass($url);
                                        $commission_result=$clean_commission*$result;
                                        if($commission_result>0)
                                        {
                                            $commission_expenseData=[
                                                'date'=>$clean_date,
                                                'type'=>'expense',
                                                'currency'=>$clean_currency,
                                                'amount'=>$commission_result,
                                                'category'=>'commission',
                                                'account'=>$source['id'],
                                                'description'=>'Commission',
                                                'user_id'=>$session->get('id')
                                            ];
                                            $EX_Model=new Expenses_Model();
                                            $EX_Model->insert($commission_expenseData);
                                        }
                                        $result = $clean_amount * $result;
                                        $sourceData = [
                                            'amount' => $source['amount'] - $result-$commission_result
                                        ];
                                        if($account['currency']=='lira')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-TRY';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='euro')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$clean_amount
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='dollar'||$account['currency']=='tether')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-USD';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $result = round($result, 2);
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='ethereum')
                                        {

                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        elseif ($account['currency']=='bitcoin')
                                        {
                                            $url = 'https://www.google.com/finance/quote/EUR-BTC';
                                            $result = $this->getDivContentByClass($url);
                                            $result = $clean_amount * $result;
                                            $accountData=[
                                                'amount'=>$account['amount']+$result
                                            ];
                                            $accountModel->update($clean_source, $sourceData);
                                            $accountModel->update($clean_account, $accountData);
                                            $remittanceModel->insert($remittanceData);
                                            return redirect()->to('dashboard');
                                        }
                                        else
                                        {
                                            $data['validation_error']['impossible_transaction'] = 'You cannot make this transaction';
                                            $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                            $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                            return view('dashboard', $data);
                                        }
                                    }
                                }
                                else
                                {
                                    $data['validation_error']['insufficient_amount'] = 'You do not have enough money in your source account';
                                    $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                    $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                    return view('dashboard', $data);
                                }
                            }
                            else
                            {
                                $data['validation_error']['same_account'] = 'You cannot make a remittance to the same account';
                                $data['accounts'] = $accountModel->where('user_id', $session->get('id'))->findAll();
                                $data['debts'] = $debtModel->where('user_id', $session->get('id'))->findAll();
                                return view('dashboard', $data);
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
                }
            }

        }
    }
}