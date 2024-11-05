document.addEventListener("DOMContentLoaded", () => {
    carregarPerguntas();

    document.getElementById("usuarioForm").addEventListener("submit", function (e) {
        e.preventDefault();
        enviarFormulario(this, "usuario");
    });

    document.getElementById("perguntaForm").addEventListener("submit", function (e) {
        e.preventDefault();
        enviarFormulario(this, "pergunta");
    });
});

function enviarFormulario(form, tipo) {
    const formData = new FormData(form);
    formData.append("tipo", tipo);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "server.php", true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            carregarPerguntas();
            form.reset();
        }
    };
    xhr.send(formData);
}

function carregarPerguntas() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "server.php", true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            document.getElementById("perguntasTabela").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function excluirPergunta(id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `server.php?delete=${id}`, true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            carregarPerguntas();
        }
    };
    xhr.send();
}

function editarPergunta(id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `server.php?edit=${id}`, true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            const dados = JSON.parse(xhr.responseText);
            document.getElementById("texto").value = dados.texto;
            document.getElementById("respostaA").value = dados.respostas[0];
            document.getElementById("respostaB").value = dados.respostas[1];
            document.getElementById("respostaC").value = dados.respostas[2];
            document.getElementById("respostaD").value = dados.respostas[3];
            document.getElementById("respostaCorreta").value = dados.respostaCorreta;
        }
    };
    xhr.send();
}
