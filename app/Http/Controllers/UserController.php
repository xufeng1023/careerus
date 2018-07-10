<?php

namespace App\Http\Controllers;

use App\{User, Plan, Invoice};

class UserController extends Controller
{
    public function all()
    {
        if(request('id')) $users[] = User::find(request('id'));
        else $users = User::all();

        return view('admin.users', compact('users'));
    }

    public function save()
    {
        User::create(request()->all());

        return back();
    }

    public function update(User $user)
    {
        $user->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }

    public function toggleSuspend(User $user)
    {
        $user->suspended = !$user->suspended;
        $user->save();
    }

    public function applies()
    {
        $applies = auth()->user()->applies()->orderBy('created_at','desc')->paginate(15);

        $applies->load('post');

        return view('dashboard.applies', compact('applies'));
    }

    public function favorites()
    {
        return view('dashboard.favorites');
    }

    public function account()
    {
        return view('dashboard.account');
    }

    public function payment()
    {
        $plans = Plan::all();
        return view('dashboard.payment', compact('plans'));
    }

    public function accountUpdate()
    {
        request()->validate([
            'name' => 'required|regex:/^[a-zA-Z]{1}[a-zA-Z ]*$/|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id().',id',
            'phone' => 'required|regex:/^[1-9]{1}[0-9]{9}$/|unique:users,phone,'.auth()->id().',id'
        ]);

        auth()->user()->update(request()->all());

        return [trans('front.updated ok')];
    }

    public function passwordUpdate()
    {
        request()->validate([
            'oldPass' => 'required_if:login_provider,null|string|min:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if(!auth()->user()->login_provider) {

            $credentials = ['email' => auth()->user()->email, 'password' => request('oldPass')];

            if(!\Auth::once($credentials)) {
                return response(['errors' => ['oldPass' => trans('front.old pass bad')]], 422);
            }
        }

        auth()->user()->update(['password' => \Hash::make(request('password'))]);

        return [trans('front.updated ok')];
    }

    public function resumeDownload()
    {
        try {
            return \Storage::download(request('r'));
        } catch(\Exception $e) {
            return back();
        }
    }

    public function resumeUpdate()
    {
        request()->validate([
            'resume' => 'required|file|mimes:doc,docx,txt,pdf,rtf'
        ]);

        auth()->user()->update(['resume' => request('resume')->store('resumes')]);

        return [trans('front.updated ok')];
    }

    public function updateCardInfo()
    {
        $user = auth()->user();

        try{
            if($user->stripe_id) $user->updateCard(request('token'));

            else $user->createAsStripeCustomer(request('token'));
        } catch(\Exception $e) {
            return response(trans('front.cc invalid'), 402);
        }

        return [
            'cardLabel' => $user->cardLabel(),
            'msg' => trans('front.updated ok')
        ];
    }

    public function buy()
    {
        $user = auth()->user();

        if(!$user->stripe_id) return response(trans('front.no card'), 402);

        try{
            $plan = Plan::findOrFail(request('plan'));
        } catch(\Exception $e) {
            return response(trans('front.plan invalid'), 402);
        }

        try {
            $result = $user->invoiceFor($plan->name, $plan->price*100);
            $invoice = new Invoice;
            $invoice->user_id = auth()->id();
            $invoice->plan = $plan->name;
            $invoice->price = $plan->price;
            $invoice->invoice_id = $result->id;
            $invoice->save();
        } catch(\Exception $e) {
            return response(trans('front.payment failed'), 402);
        }
        
        $user->increment('points', $plan->points);

        return [
            'points' => $user->points,
            'invoice' => $invoice,
            'msg' => trans('front.bought ok')
        ];
    }

    public function invoices()
    {
        return auth()->user()->invoices()->orderBy('created_at', 'desc')->get();
    }

    public function invoice(Invoice $invoice)
    {
        return auth()->user()->downloadInvoice($invoice->invoice_id, [
            'vendor'  => config('app.name'),
            'product' => $invoice->plan,
        ]);
    }
}
