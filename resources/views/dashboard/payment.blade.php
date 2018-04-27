@extends('layouts.dashboard')

@section('substyle')
<link href="{{ asset('css/stripe.css') }}" rel="stylesheet">
@endsection

@section('subcontent')
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <h6 class="border-bottom border-gray pb-2 mb-4 d-flex justify-content-between">
                    <div>
                        <span data-feather="credit-card"></span> {{ __('front.cc') }}
                        <span id="cardLabel" class="badge badge-pill badge-secondary">{{ auth()->user()->cardLabel() }}</span>
                    </div>
                    <button id="ccbtn" type="submit" class="btn btn-primary btn-sm" form="ccForm">{{ __('front.update') }}</button>
                </h6>
                <form id="ccForm" action="/dashboard/card" method="post" @submit="onSubmit">
                    <div class="form-group">
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                        <input type="hidden" name="payToken" id="payToken" required>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <h6 class="border-bottom border-gray pb-2 mb-4 d-flex justify-content-between">
                    <div>
                        <span data-feather="award"></span> {{ __('front.points') }}
                        <span id="userPoints" class="badge badge-pill badge-secondary">{{ auth()->user()->points }}</span>
                    </div>
                    <button id="paybtn" type="submit" class="btn btn-primary btn-sm" form="paymentForm">{{ __('front.buy') }}</button>
                </h6>

                <form action="/dashboard/buy" class="mx-auto w-75" method="post" id="paymentForm" onsubmit="onSubmit(event)">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{ __('front.points options') }}</label>
                        <div class="col-sm-6">
                            <select name="plan" class="form-control" required>
                                <option value="">{{ __('front.points please choose') }}</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name.' - $'.$plan->price }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="cardLast4" id="cardLast4" value="{{ auth()->user()->card_last_four }}">
                </form>
            </div>
        </div>
    </div>

    <table v-if="invoices.length" class="table table-sm">
        <caption><span data-feather="list"></span> {{ __('front.payment list') }}</caption>
        <thead class="thead-light">
            <tr><th>{{ __('front.points') }}</th><th>{{ __('front.price') }}</th><th>{{ __('front.date') }}</th><th></th></tr>
        </thead>
        <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id">
                <td v-text="invoice.plan"></td>
                <td v-text="invoice.price"></td>
                <td v-text="invoice.created_at"></td>
                <td>
                    <a target="_blank" :href="invoice.invoice_id | downloadLink">
                        <span data-feather="download"></span>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script>
    var cardLast4 = document.getElementById('cardLast4');
    const app = new Vue({
        el: '#app',
        data: {
            invoices: '',
            card: ''
        },
        filters: {
            downloadLink: function($value) {
                return '/dashboard/payment/invoice/' + $value;
            }
        },
        mounted() {
            this.card = window.stripe.elements({locale:'zh'}).create('card');
            this.card.mount('#card-element');
            this.card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            this.getInvoices();
        },
        methods: {
            onSubmit(e) {
                let _this = this;
                e.preventDefault();
                window.stripe.createToken(this.card).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        window.helper.disable('ccbtn');
                        const action = e.target.getAttribute('action');
                        $.ajax(action, {
                            type: 'post',
                            context: this,
                            data: {token: result.token.id},
                            error: function(data) {
                                toastr.error(data.responseText);
                            },
                            success: function(data) {
                                let cardLabel = document.getElementById('cardLabel');
                                cardLabel.innerText = data.cardLabel;
                                cardLast4.value = data.cardLabel
                                _this.card.clear();
                                toastr.success(data.msg);
                            },
                            complete: function() {
                                window.helper.enable('ccbtn');
                            }
                        })
                    }
                });
            },
            getInvoices() {
                $.ajax('/dashboard/payment/invoices', {
                    context: this,
                    success: function(data) {
                        this.invoices = data;
                    }
                })
            }
        }
    });

    function onSubmit(e) {
        e.preventDefault();
        if(!cardLast4.value) {
            let cardElement = document.getElementById('card-element');
            cardElement.classList.add('StripeElement--invalid');
            return toastr.error('请先添加信用卡信息');
        }
        window.helper.disable('paybtn');
        $.ajax(e.target.getAttribute('action'), {
            type: 'post',
            data: $(e.target).serialize(),
            error: function(data) {
                this.errors = JSON.parse(data.responseText).errors;
            },
            success: function(data) {
                var userPoints = document.getElementById('userPoints');
                userPoints.innerText = data.points;
                toastr.success(data.msg);
                app.invoices.unshift(data.invoice);
            },
            complete: function() {
                window.helper.enable('paybtn');
            }
        });
    }
</script>
@endsection
