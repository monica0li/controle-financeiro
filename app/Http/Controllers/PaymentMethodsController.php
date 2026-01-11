<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        // MODIFICADO: Buscar apenas do usuário atual
        $paymentMethods = PaymentMethod::where('user_id', auth()->id())
            ->orderBy('is_card', 'desc')
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
            'name' => [
                'required',
                'string',
                'max:100',
                // ADICIONADO: Validação única por usuário
                Rule::unique('payment_methods')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
            'is_card' => 'nullable|boolean',
            'card_type' => 'required_if:is_card,true|in:credit,debit',
            'active' => 'boolean'
        ]);

        // Garantir que is_card tenha valor
        if (!isset($validated['is_card'])) {
            $validated['is_card'] = false;
        }

        // Se não for cartão, limpar card_type
        if (!$validated['is_card']) {
            $validated['card_type'] = null;
        }

        // ADICIONADO: Associar ao usuário atual
        $validated['user_id'] = auth()->id();

        PaymentMethod::create($validated);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar esta forma de pagamento.');
        }
        
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar esta forma de pagamento.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                // ADICIONADO: Validação única por usuário, ignorando o próprio registro
                Rule::unique('payment_methods')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($paymentMethod->id)
            ],
            'is_card' => 'nullable|boolean',
            'card_type' => 'required_if:is_card,true|in:credit,debit',
            'active' => 'boolean'
        ]);

        // Garantir que is_card tenha valor
        if (!isset($validated['is_card'])) {
            $validated['is_card'] = false;
        }

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
        // ADICIONADO: Verificar se pertence ao usuário
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para excluir esta forma de pagamento.');
        }

        // Em vez de excluir, apenas desativa
        $paymentMethod->update(['active' => false]);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento desativada com sucesso!');
    }

    public function activate(PaymentMethod $paymentMethod)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($paymentMethod->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para reativar esta forma de pagamento.');
        }

        $paymentMethod->update(['active' => true]);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Forma de pagamento reativada com sucesso!');
    }
}