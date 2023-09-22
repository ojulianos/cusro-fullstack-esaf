let container = '';
let tableData = document.getElementById('table-data');
let tableDataTd = document.querySelectorAll('#table-data td');
let message = document.getElementById('message');
let btnInicio = document.getElementById('btn-inicio');
let btnReset = document.getElementById('btn-reset');
let jogador = 0;
let vencedor = 0;
let possibilidades = [
    [0,1,2], // linha 1
    [3,4,5], // linha 2 
    [6,7,8], // linha 3
    [0,3,6], // coluna 1
    [1,4,7], // coluna 2
    [2,5,8], // coluna 3
    [0,4,8], // diagonal 1
    [2,4,6], // diagonal 2
];

function iniciarJogo() {
    tableData.classList.remove('hide');
    btnReset.classList.remove('hide');
    btnInicio.classList.add('hide');
    jogador = 0;
    vencedor = 0;
    message.innerText = 'Vez do Jogador X';

    for (let i=0; i < tableDataTd.length; i++) {
        tableDataTd[i].innerText = '';
    }
}

function cancelarJogo() {
    tableData.classList.add('hide');
    btnReset.classList.add('hide');
    btnInicio.classList.remove('hide');
    message.innerText = 'Clique no botão para inicar o jogo.';
    btnReset.textContent = 'Cancelar Jogo';

    for (let i=0; i<tableDataTd.length; i++) {
        tableDataTd[i].classList.remove('venceu');
        tableDataTd[i].classList.remove('velha');
    }
}

for (let i=0; i < tableDataTd.length; i++) {
    tableDataTd[i].addEventListener('click', function(){
        if (vencedor === 1) {
            return;
        }
        
        if (tableDataTd[i].innerText.length <= 0) {
            const proximoJogador = jogador === 0 ? 'X' : 'O';
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

            message.innerHTML = `Vez do jogador ${proximoJogador}`;
        } else {
            alert('Você não pode jogar em uma casa ocupada');
        }

        if (validarVelha()) {
            for (let i=0; i < tableDataTd.length; i++) {
                tableDataTd[i].classList.add('velha');
            }
            message.innerHTML = 'DEU VELHA';
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
        x.classList.add('venceu');
        y.classList.add('venceu');
        z.classList.add('venceu');
        return 'Jogador X Venceu';
    }

    if (x.innerText == 'O' && y.innerText == 'O' && z.innerText == 'O') {
        x.classList.add('venceu');
        y.classList.add('venceu');
        z.classList.add('venceu');
        return 'Jogador O Venceu';
    }

    return false;
}


