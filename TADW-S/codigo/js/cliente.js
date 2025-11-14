// cliente.js

// Função para validar data de nascimento (mínimo 16 anos)
function validarIdade(dataNascimento) {
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);

    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const m = hoje.getMonth() - nascimento.getMonth();

    if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) {
        idade--;
    }
    return idade >= 16;
}

// Impedir caracteres não numéricos no telefone
function somenteNumeros(event) {
    event.target.value = event.target.value.replace(/\D/g, "");
}

// Função para aplicar máscara de telefone (11 dígitos)
function aplicarMascaraTelefone(event) {
    let valor = event.target.value.replace(/\D/g, ""); // remove tudo que não for número
    if (valor.length > 11) valor = valor.slice(0, 11); // limita a 11 dígitos

    if (valor.length > 6) {
        valor = valor.replace(/(\d{2})(\d{5})(\d{0,4})/, "$1 $2-$3");
    } else if (valor.length > 2) {
        valor = valor.replace(/(\d{2})(\d{0,5})/, "$1 $2");
    }
    event.target.value = valor.trim();
}

// Validação principal do formulário
function validarFormulario(event) {
    const data = document.getElementById("data_ani").value;
    const telefone = document.getElementById("telefone").value.replace(/\D/g, ""); // só números

    // Valida idade
    if (!validarIdade(data)) {
        alert("Você precisa ter pelo menos 16 anos para criar uma conta.");
        event.preventDefault();
        return false;
    }

    // Valida tamanho do telefone
    if (telefone.length > 11) {
        alert("O telefone deve conter no máximo 11 números.");
        event.preventDefault();
        return false;
    }

    return true;
}

// Aplicar eventos após carregar a página
window.onload = function () {
    const telefoneInput = document.getElementById("telefone");
    const form = document.querySelector("form");

    telefoneInput.addEventListener("input", somenteNumeros);
    telefoneInput.addEventListener("input", aplicarMascaraTelefone);
    form.addEventListener("submit", validarFormulario);
};
