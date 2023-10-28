let $ = function(element) { return document.querySelector(element); };
let $$ = function(element) { return document.querySelectorAll(element); };

let sumir = $('#sumir');
let listaDeItensLi = $$('#listaDeItens li');


function inicio(){
    let localData = localStorage.getItem('LISTA-DE-TAREFAS')
    if (localData) {
        document.querySelector('#sumir').classList.add('d-none');
        localData = JSON.parse(localData);

        for (let i = 0; i < localData.length; i++) {
            let templateItem = `
                <input class="form-check-input" type="checkbox" id="finalizaItem${i}" onclick="finalizaItem(${i})">
                <label class="form-check-label" for="finalizaItem${i}">
                    <span>${localData[i].tarefa}</span> <br>
                    <small>${localData[i].descricao}</small>
                </label>
                <span class="ms-auto text-end">
                    <span class="badge bg-primary rounded-pill" role="button" onclick="editItem(${i})">
                        <i class="bi bi-pencil"></i>
                    </span>
                    <span class="badge bg-danger rounded-pill" role="button" onclick="deleteItem(${i})">
                        <i class="bi bi-trash"></i>
                    </span>
                </span>
            `;
            let itemDaLista = document.createElement('li');
            itemDaLista.classList.add('list-group-item', 'hstack', 'gap-2', localData[i].tipo);
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
    $('#itemId' + id).remove();
    let posicao = $$('#listaDeItens li').length;
    if (posicao === 0) {
        sumir.classList.remove('d-none');
    }
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
    let checkboxAtual = document.querySelector('#finalizaItem' + id + ':checked');
    if (checkboxAtual) {
        document.querySelector('#itemId'+id).classList.add('text-decoration-line-through');
    } else {
        document.querySelector('#itemId'+id).classList.remove('text-decoration-line-through');
    }

    let itensFinalizados = document.querySelectorAll('#listaDeItens input[type=checkbox]:checked').length;
    document.querySelector('#prontos').textContent = itensFinalizados;
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
                <span class="badge bg-primary rounded-pill" role="button" onclick="editItem(${posicao})">
                    <i class="bi bi-pencil"></i>
                </span>
                <span class="badge bg-danger rounded-pill" role="button" onclick="deleteItem(${posicao})">
                    <i class="bi bi-trash"></i>
                </span>
            </span>
        `;

        let localStorageItem = localStorage.getItem('LISTA-DE-TAREFAS');
        if (localStorageItem) {
            localStorage.setItem('LISTA-DE-TAREFAS', '');
        }
    
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
            localStorage.setItem('LISTA-DE-TAREFAS', JSON.stringify(localStorageItem));

        } else {
            let itemDaLista = document.createElement('li');
            itemDaLista.classList.add('list-group-item', 'hstack', 'gap-2', sTipoDeTarefa.value);
            itemDaLista.id = 'itemId' + posicao;
            itemDaLista.innerHTML = templateItem;
            listaDeItens.appendChild(itemDaLista);

            if (localStorageItem.trim().length > 0) {
                localStorageItem = JSON.parse(localStorageItem);
                localStorageItem.push(itensListaTarefa);
                localStorage.setItem('LISTA-DE-TAREFAS', JSON.stringify(localStorageItem));
            } else {
                let itemNovoNaLista = [itensListaTarefa];
                localStorage.setItem('LISTA-DE-TAREFAS', JSON.stringify(itemNovoNaLista));
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