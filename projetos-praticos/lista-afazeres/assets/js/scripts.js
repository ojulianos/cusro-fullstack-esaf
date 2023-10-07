function mudarTema() {
    let html = document.querySelector('html');
    let btnMudaTema = document.querySelector('#btnMudaTema');
    
    if (html.getAttribute('data-bs-theme') === 'dark') {
        html.setAttribute('data-bs-theme', 'light');
        btnMudaTema.classList.remove('btn-light');
        btnMudaTema.classList.add('btn-dark');
    } else {
        html.setAttribute('data-bs-theme', 'dark');
        btnMudaTema.classList.remove('btn-dark');
        btnMudaTema.classList.add('btn-light');
    }

    // html.setAttribute('data-bs-theme', ehDark(html) ? 'light' : 'dark');
    // btnMudaTema.classList.remove(ehDark(html) ? 'btn-light' : 'btn-dark');
    // btnMudaTema.classList.add(ehDark(html) ? 'btn-dark' : 'btn-light');
}

function ehDark(html) {
   return html.getAttribute('data-bs-theme') === 'dark';
}

const formAddItem = document.querySelector('#formAddItem');
formAddItem.addEventListener('submit', function(e) {
    e.preventDefault();

    let iTituloTarefa = document.querySelector('#iTituloTarefa');
    let tDescricaoTarefa = document.querySelector('#tDescricaoTarefa');
    let sTipoDeTarefa = document.querySelector('#sTipoDeTarefa');
    let listaDeItens = document.querySelector('#listaDeItens');

    let templateItem = `
        <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
        <label class="form-check-label" for="firstCheckbox">
            ${iTituloTarefa.value} <br>
            <small>
                ${tDescricaoTarefa.value}
            </small>
        </label>
        <span class="ms-auto text-end">
            <span class="badge bg-primary rounded-pill" role="button">
                <i class="bi bi-pencil"></i>
            </span>
            <span class="badge bg-danger rounded-pill" role="button">
                <i class="bi bi-trash"></i>
            </span>
        </span>
    `;

    let itemDaLista = document.createElement('li');
    itemDaLista.classList.add('list-group-item', 'hstack', 'gap-2', sTipoDeTarefa.value);
    itemDaLista.innerHTML = templateItem;

    listaDeItens.appendChild(itemDaLista);

});