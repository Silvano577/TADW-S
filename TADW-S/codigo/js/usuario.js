// ======== Validação de Senha ========
function validarSenha() {
    const senha = document.getElementById("senha").value;
    const confirmar = document.getElementById("confirmar_senha").value;

    // se ambos foram preenchidos, compara
    if (senha !== "" || confirmar !== "") {
        if (senha !== confirmar) {
            alert("As senhas não coincidem. Por favor, digite novamente.");
            return false;
        }
    }
    return true;
}
