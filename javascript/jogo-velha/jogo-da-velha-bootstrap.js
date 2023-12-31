let tableData = document.getElementById('table-data');
let tableDataTd = document.querySelectorAll('#table-data td');
let message = document.getElementById('message');
let btnInicio = document.getElementById('btn-inicio');
let btnReset = document.getElementById('btn-reset');
let jogador = 0;
let vencedor = 0;
let possibilidades = [
    [0,1,2], [3,4,5], [6,7,8], 
    [0,3,6], [1,4,7], [2,5,8], 
    [0,4,8], [2,4,6], 
];
let placar = { X: 0, O: 0, V: 0}

function iniciarJogo() {
    tableData.classList.remove('d-none');
    btnReset.classList.remove('d-none');
    btnInicio.classList.add('d-none');
    jogador = 0;
    vencedor = 0;
    message.innerText = 'Vez do Jogador X';
    document.querySelector('#emJogo').classList.remove('d-none');

    for (let i=0; i < tableDataTd.length; i++) {
        tableDataTd[i].innerText = '';
    }
}

function cancelarJogo() {
    tableData.classList.add('d-none');
    btnReset.classList.add('d-none');
    btnInicio.classList.remove('d-none');
    message.innerText = 'Clique no botão para inicar o jogo.';
    btnReset.textContent = 'Cancelar Jogo';

    for (let i=0; i<tableDataTd.length; i++) {
        tableDataTd[i].classList.remove('bg-success');
        tableDataTd[i].classList.remove('bg-danger');
    }
}

for (let i=0; i < tableDataTd.length; i++) {
    tableDataTd[i].addEventListener('click', function(){
        if (vencedor === 1) {
            return;
        }
        
        if (tableDataTd[i].innerText.length <= 0) {
            const proximoJogador = jogador === 1 ? 'O' : 'X';
            jogador = jogador === 0 ? 1 : 0;
            tableDataTd[i].innerText = proximoJogador;
            
            for (let j=0; j < possibilidades.length; j++) {
                linha1 = validarItens(
                    tableDataTd[possibilidades[j][0]],
                    tableDataTd[possibilidades[j][1]],
                    tableDataTd[possibilidades[j][2]]
                );
                if (linha1 !== false ) {
                    vencedor = 1;
                    message.innerHTML = linha1;
                    btnReset.textContent = 'Reiniciar';
                    return;
                }
            }

            jogadorMessage = proximoJogador === 'X' ? 'O' : 'X';
            message.innerHTML = `Vez do jogador ${jogadorMessage}`;
        } else {
            alert('Você não pode jogar em uma casa ocupada');
        }

        if (validarVelha()) {
            for (let i=0; i < tableDataTd.length; i++) {
                tableDataTd[i].classList.add('bg-danger');
            }
            message.innerHTML = 'DEU VELHA';
            placar.V++;
            document.querySelector('#placarV').innerText = placar.V;
        }
    });
}

function validarVelha() {
    let velha = 0;
    for (let i=0; i < tableDataTd.length; i++) {
        if (tableDataTd[i].innerText.trim() != '') {
            velha++;
        }
    }

    return velha === 9;
}

function validarItens(x, y ,z) {
    if (x.innerText == 'X' && y.innerText == 'X' && z.innerText == 'X') {
        x.classList.add('bg-success');
        y.classList.add('bg-success');
        z.classList.add('bg-success');
        placar.X++;
        document.querySelector('#placarX').innerText = placar.X;
        return 'Jogador X Venceu';
    }

    if (x.innerText == 'O' && y.innerText == 'O' && z.innerText == 'O') {
        x.classList.add('bg-success');
        y.classList.add('bg-success');
        z.classList.add('bg-success');
        placar.O++;
        document.querySelector('#placarO').innerText = placar.O;
        return 'Jogador O Venceu';
    }

    return false;
}


