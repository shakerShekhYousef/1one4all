<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaymentPaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig(['settings']);

    }

    public function paypal(Request $request)
    {
        $pay = $request->amount;
        $pay = $pay * 0.27;
        $user = User::where('id', $request->user_id)->first();
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1')/** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            /** unit price **/
            ->setPrice($pay);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($pay);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('One4all');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('status'))/** Specify return URL **/
        ->setCancelUrl(URL::route('status'));
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $created_payment = Payment::where([
                ['request_id', $request->request_id],
                ])->first();
            if($created_payment){
                return response()->json('Payment request has already been sent',400);
            }
            $payment->create($this->_api_context);
            Payment::create([
                'user_id' => $user->id,
                'request_id' => $request->request_id,
                'payment_method' => 'PayPal',
                'amount' => $request->amount,
                'payment_id' => $payment->getId(),
                'status' => 0,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        } catch (PayPalConnectionException $ex) {
            if (Config::get('app.debug')) {
                return response()->json($ex->getMessage(), 500);
            } else {
                return response()->json('Some error occur, sorry for inconvenient', 500);
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        return response()->json('Unknown error occurred', 500);

    }

    public function getPaymentStatus(Request $request)
    {
        $payment_id = $_GET['paymentId'];
        $payment = \PayPal\Api\Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        try {
            if ($result->getState() == 'approved') {
                $currency = Currency::first();
                $lastPayment = Payment::orderBy('created_at', 'desc')->first();
                if (!$lastPayment) {
                    // We get here if there is no order at all
                    // If there is no number set it to 0, which will be 1 at the end.
                    $number = 0;
                } else {
                    $number = substr($lastPayment->order_id, 3);
                }
                $paymentDB = Payment::where('payment_id', $payment_id)->first();
                $paymentDB->order_id = '#' . sprintf("%08d", intval($number) + 1);
                $paymentDB->payment_method = 'PayPal';
                $paymentDB->transaction_id = $payment_id;
                $paymentDB->status = 1;
                $paymentDB->save();
                $request_update = \App\Models\Request::where('id', $paymentDB->request_id)->first();
                $request_update->request_type = "Paid";
                $request_update->save();
                $plan = Plan::create([
                    'user_id' => $request_update->player_id,
                    'trainer_id' => $request_update->trainer_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $message = $request_update->player->name . ' has completed the payment process, click to add a plan.';
                $type = 'Payment Completed';
                $sender = $request_update->player_id;
                RequestController::sendNotification($request_update->trainer_id, $sender, $message, $type, $request_update->id);
                return view('success');
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }


    }
}
