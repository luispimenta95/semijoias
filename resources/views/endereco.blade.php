@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Cadastro</h3>

    <form id="form-endereco" class="row g-3" action="{{ route('usuarios.endereco.store') }}" method="POST">
        @csrf
        {{-- Coluna Endereço --}}
        <div class="col-md-6">
            <h5 class="mb-3">Cadastro de Endereço</h5>

            {{-- Campo CEP --}}
            <div class="mb-3" id="cep-group">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" maxlength="9" placeholder="00000-000">
                <div id="cep-feedback" class="text-danger small mt-1"></div>
            </div>

            <div id="dados-endereco" style="display:none;">
                <div class="mb-3">
                    <label for="logradouro" class="form-label">Logradouro</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                </div>
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                </div>
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" required>
                </div>
                <!-- Removido campo CEP duplicado -->
                <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>
                <div class="mb-3">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento">
                </div>
            </div>

        </div>
</div>

{{-- Coluna Dados Pessoais --}}
<div class="col-md-6">
    <h5 class="mb-3">Dados Pessoais</h5>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required>
    </div>
</div>

{{-- Botão Unificado --}}
<div class="col-12 text-center mt-4">
    <button type="submit" class="btn btn-primary btn-lg">Salvar Cadastro</button>
</div>

</form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cepInput = document.getElementById('cep');
        const dadosEndereco = document.getElementById('dados-endereco');
        const feedback = document.getElementById('cep-feedback');

        cepInput.addEventListener('input', async (e) => {
            let cep = e.target.value.replace(/\D/g, '');
            if (cep.length === 8) {
                feedback.textContent = 'Buscando endereço...';
                try {
                    const response = await fetch(`/api/cep/${cep}`);
                    if (!response.ok) throw new Error('CEP não encontrado');
                    const data = await response.json();

                    dadosEndereco.style.display = 'block';
                    document.getElementById('logradouro').value = data.street || '';
                    document.getElementById('bairro').value = data.neighborhood || '';
                    document.getElementById('endereco').value = data.street || '';
                    document.getElementById('cidade').value = data.city || '';
                    document.getElementById('estado').value = data.uf || '';
                    // O campo CEP já está preenchido
                    feedback.textContent = '';
                } catch (error) {
                    feedback.textContent = 'CEP inválido ou não encontrado.';
                    dadosEndereco.style.display = 'none';
                }
            } else {
                feedback.textContent = '';
                dadosEndereco.style.display = 'none';
            }
        });
    });
</script>
@endsection