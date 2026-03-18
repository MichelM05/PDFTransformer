document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById('pdf');
    const fileNameDisplay = document.getElementById('file-name');
    const fileLabel = document.getElementById('file-label'); // Label
    const btnRemove = document.getElementById('btn-remove'); // Botão X

    // 1. Quando o arquivo mudar (selecionar)
    if (input) {
        input.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                // Muda o nome exibido
                fileNameDisplay.textContent = this.files[0].name;
                // Muda o texto do LABEL
                fileLabel.style.color = "#FFF   ";
                fileLabel.textContent = "Arquivo selecionado";
                fileLabel.style.backgroundColor = "#10b981"; // Muda cor do label pra verde
                // Mostra o botão "X"
                btnRemove.style.display = "inline-block";
            }
        });
    }

    // 2. Quando clicar no botão "X" (remover)
    if (btnRemove) {
        btnRemove.addEventListener('click', function() {
            // Limpa o input de arquivo (Mágica: para resetar um input file, igualamos o value a vazio)
            input.value = "";

            // Volta os textos ao normal usando os atributos de dados (centralizado no Blade)
            fileNameDisplay.textContent = fileNameDisplay.getAttribute('data-original-text');
            fileLabel.textContent = fileLabel.getAttribute('data-original-text');
            fileLabel.style.backgroundColor = ""; // Volta a cor original
            fileLabel.style.color = "";

            // Esconde o botão "X" novamente
            this.style.display = "none";
        });
    }
});
