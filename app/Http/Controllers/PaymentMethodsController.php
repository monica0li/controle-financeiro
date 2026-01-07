<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('is_card', 'desc')
            ->orderBy('name')
            ->get();
            
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_card' => 'boolean',
            'card_type' => 'required_if:is_card,true|in:credit,debit',
            'active' => 'boolean'
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('payment_methods')->ignore($paymentMethod->id)
            ],
            'is_card' => 'boolean',
            'card_type' => 'required_if:is_card,true|in:credit,debit',
            'active' => 'boolean'
        ]);

        // Se não for cartão, limpar card_type
        if (!$validated['is_card']) {
            $validated['card_type'] = null;
        }

        $paymentMethod->update($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        // Em vez de excluir, apenas desativa
        $paymentMethod->update(['active' => false]);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento desativada com sucesso!');
    }

    // NOVO: Método para reativar
    public function activate(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['active' => true]);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento reativada com sucesso!');
    }
}