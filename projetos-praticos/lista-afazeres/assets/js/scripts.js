let $ = function(element) { return document.querySelector(element); };
let $$ = function(element) { return document.querySelectorAll(element); };

let sumir = $('#sumir');
let listaDeItensLi = $$('#listaDeItens li');
const CHAVE_BANCO_DADOS = 'LISTA-DE-TAREFAS';

function inicio(){
    let localData = localStorage.getItem(CHAVE_BANCO_DADOS);
    // [] '' false undefine null
    if (!localData) {
        localStorage.setItem(CHAVE_BANCO_DADOS, '[]');
        localData = localStorage.getItem(CHAVE_BANCO_DADOS);
    }

    if (localData) {
        $('#listaDeItens').innerHTML = '';
        $('#sumir').classList.add('d-none');
        localData = JSON.parse(localData);

        for (let i = 0; i < localData.length; i++) {
            checked = '';
            btnEdit = '';
            classChecked = '-';
            if (localData[i].status == 1) {
                checked = 'checked';
                btnEdit = 'd-none';
                classChecked = 'text-decoration-line-through';
            }

            let templateItem = `
                <input class="form-check-input" type="checkbox" id="finalizaItem${i}" onclick="finalizaItem(${i})" ${checked}>
                <label class="form-check-label" for="finalizaItem${i}">
                    <span>${localData[i].tarefa}</span> <br>
                    <small>${localData[i].descricao}</small>
                </label>
                <span class="ms-auto text-end">
                    <span class="badge bg-primary rounded-pill ${btnEdit}" id="btnEdit${i}" role="button" onclick="editItem(${i})">
                        <i class="bi bi-pencil"></i>
                    </span>
                    <span class="badge bg-danger rounded-pill" role="button" onclick="deleteItem(${i})">
                        <i class="bi bi-trash"></i>
                    </span>
                </span>
            `;
            let itemDaLista = document.createElement('li');
            itemDaLista.classList.add('list-group-item', 'hstack', 'gap-2', localData[i].tipo, classChecked);
            itemDaLista.id = 'itemId' + i;
            itemDaLista.innerHTML = templateItem;
            listaDeItens.appendChild(itemDaLista);
        }
    }
}

function mudarTema() {
    let html = $('html');
    let btnMudaTema = $('#btnMudaTema');
    
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

function deleteItem(id) {
    let listaDeItens = JSON.parse(localStorage.getItem(CHAVE_BANCO_DADOS));
    listaDeItens.splice(id, 1);
    listaDeItens = JSON.stringify(listaDeItens);
    localStorage.setItem(CHAVE_BANCO_DADOS, listaDeItens);

    $('#iTituloTarefa').value = '';
    $('#tDescricaoTarefa').value = '';
    $('#sTipoDeTarefa').value = 'Selecione um tipo';
    $('#iIdItem').value = '';

    inicio();
}

function editItem(id) {
    let txtTarefaAtual = $('#itemId' + id + ' label span').textContent.trim();
    let txtDescricaoAtual = $('#itemId' + id + ' label small').textContent.trim();

    let txtTipoAtual = '';
    
    $('#itemId' + id).classList.forEach(function(element) {
        if (element === 'bg-danger' || element === 'bg-warning' || element === 'bg-primary') {
            txtTipoAtual = element;
        }
    });

    $('#iTituloTarefa').value = txtTarefaAtual;
    $('#tDescricaoTarefa').value = txtDescricaoAtual;
    $('#sTipoDeTarefa').value = txtTipoAtual;
    $('#iIdItem').value = '#itemId' + id;
}

function finalizaItem(id) {
    let checkboxAtual = $('#finalizaItem' + id + ':checked');
    let listaDeItens = JSON.parse(localStorage.getItem(CHAVE_BANCO_DADOS));

    if (checkboxAtual) {
        listaDeItens[id].status = 1;
        $('#itemId'+id).classList.add('text-decoration-line-through');
        $('#btnEdit'+id).classList.add('d-none');
    } else {
        listaDeItens[id].status = 0;
        $('#itemId'+id).classList.remove('text-decoration-line-through');
        $('#btnEdit'+id).classList.remove('d-none');
    }
    
    localStorage.setItem(CHAVE_BANCO_DADOS, JSON.stringify(listaDeItens));

    let itensFinalizados = $$('#listaDeItens input[type=checkbox]:checked').length;
    $('#prontos').textContent = itensFinalizados;
}

const formAddItem = $('#formAddItem');
formAddItem.addEventListener('submit', function(e) {
    e.preventDefault();

    let iTituloTarefa = $('#iTituloTarefa');
    let tDescricaoTarefa = $('#tDescricaoTarefa');
    let sTipoDeTarefa = $('#sTipoDeTarefa');
    let listaDeItens = $('#listaDeItens');
    let iIdItem = $('#iIdItem');
    
    if (iTituloTarefa.value.trim().length <= 1) {
        alert('Preencha o campo tarefa');
        return;
    }

    if (sTipoDeTarefa.value === 'bg-danger' || sTipoDeTarefa.value === 'bg-warning' || sTipoDeTarefa.value === 'bg-primary') {
        let posicao = $$('#listaDeItens li').length;

        let templateItem = `
            <input class="form-check-input" type="checkbox" id="finalizaItem${posicao}" onclick="finalizaItem(${posicao})">
            <label class="form-check-label" for="finalizaItem${posicao}">
                <span>${iTituloTarefa.value}</span> <br>
                <small>${tDescricaoTarefa.value}</small>
            </label>
            <span class="ms-auto text-end">
                <span class="badge bg-primary rounded-pill" role="button" id="btnEdit${posicao}" onclick="editItem(${posicao})">
                    <i class="bi bi-pencil"></i>
                </span>
                <span class="badge bg-danger rounded-pill" role="button" onclick="deleteItem(${posicao})">
                    <i class="bi bi-trash"></i>
                </span>
            </span>
        `;

        let localStorageItem = localStorage.getItem(CHAVE_BANCO_DADOS);
    
        let itensListaTarefa = {
            "tarefa": iTituloTarefa.value,
            "descricao": tDescricaoTarefa.value,
            "tipo": sTipoDeTarefa.value,
            "status": 0
        };



        if (iIdItem.value.trim().length > 0) {
            $(iIdItem.value + ' label span').textContent = iTituloTarefa.value;
            $(iIdItem.value + ' label small').textContent = tDescricaoTarefa.value;
            $(iIdItem.value).classList.remove('bg-danger', 'bg-warning', 'bg-primary');
            $(iIdItem.value).classList.add(sTipoDeTarefa.value);

            let idItemEditado = iIdItem.value.split('#itemId');

            localStorageItem = JSON.parse(localStorageItem);
            localStorageItem[idItemEditado[1]] = itensListaTarefa;
            localStorage.setItem(CHAVE_BANCO_DADOS, JSON.stringify(localStorageItem));

        } else {
            let itemDaLista = document.createElement('li');
            itemDaLista.classList.add('list-group-item', 'hstack', 'gap-2', sTipoDeTarefa.value);
            itemDaLista.id = 'itemId' + posicao;
            itemDaLista.innerHTML = templateItem;
            listaDeItens.appendChild(itemDaLista);

            if (localStorageItem.trim().length > 0) {
                localStorageItem = JSON.parse(localStorageItem);
                localStorageItem.push(itensListaTarefa);
                localStorage.setItem(CHAVE_BANCO_DADOS, JSON.stringify(localStorageItem));
            } else {
                let itemNovoNaLista = [itensListaTarefa];
                localStorage.setItem(CHAVE_BANCO_DADOS, JSON.stringify(itemNovoNaLista));
            }
        }

        if (posicao === 0) {
            sumir.classList.add('d-none');
        }

        iTituloTarefa.value = '';
        tDescricaoTarefa.value = '';
        sTipoDeTarefa.value = 'Selecione um tipo';
        iIdItem.value = '';

    } else {
        alert('Seleciona um tipo válido');
        return;
    }
});
