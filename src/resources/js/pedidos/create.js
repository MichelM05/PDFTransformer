document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('itens-container');
    const btnAddItem = document.getElementById('add-item');

    // 1. Função para Adicionar Item
    if (btnAddItem) {
        btnAddItem.addEventListener('click', function() {
            const items = container.getElementsByClassName('item-form-card');
            const itemCount = items.length;
            const originalCard = items[0];
            const newCard = originalCard.cloneNode(true);

            // Limpar valores e atualizar índices dos nomes [0] -> [1], [2]...
            newCard.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.name = input.name.replace(/\[\d+\]/, '[' + itemCount + ']');
            });

            // Garantir que o novo item comece expandido
            newCard.classList.remove('item-collapsed');

            container.appendChild(newCard);
        });
    }

    // 2. Delegação de Eventos para Excluir e Minimizar (Melhor performance)
    if (container) {
        container.addEventListener('click', function(e) {
            // Botão Excluir
            if (e.target.closest('.btn-remove-item')) {
                const card = e.target.closest('.item-form-card');
                const items = container.getElementsByClassName('item-form-card');

                // Não deixa excluir se for o único item
                if (items.length > 1) {
                    card.remove();
                } else {
                    alert('O pedido deve ter ao menos um item.');
                }
            }

            // Botão Minimizar/Expandir
            if (e.target.closest('.btn-toggle-item')) {
                const card = e.target.closest('.item-form-card');
                card.classList.toggle('item-collapsed');
            }
        });
    }
});
