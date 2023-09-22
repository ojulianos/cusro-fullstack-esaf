let container = '';
let tableData = document.getElementById('table-data');
let tableDataTd = document.querySelectorAll('#table-data td');
let message = document.getElementById('message');
let btnInicio = document.getElementById('btn-inicio');
let btnReset = document.getElementById('btn-reset');
let jogador = 0;
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

    for (let i=0; i < 9; i++) {
        tableDataTd[i].innerText = '';
    }
}

function cancelarJogo() {
    tableData.classList.add('hide');
    btnReset.classList.add('hide');
    btnInicio.classList.remove('hide');
}

for (let i=0; i < tableDataTd.length; i++) {
    tableDataTd[i].addEventListener('click', function(){
        if (tableDataTd[i].innerHTML.length <= 0) {
            const proximoJogador = jogador === 0 ? 'X' : 'O';
            jogador = jogador === 0 ? 1 : 0;
            tableDataTd[i].innerHTML = proximoJogador;
            message.innerHTML = `Vez do jogador ${proximoJogador}`;
    
            // validar linhas
            linha1 = validarItens(tableDataTd[0], tableDataTd[1], tableDataTd[2]);
            if (linha1 !== false ) { return alert(linha1); }

            /*
            if (jogador === 0) {
                jogador = 1;
                tableDataTd[i].innerHTML = 'X';
                message.innerHTML = `Vez do jogador X`;
            } else {
                jogador = 0;
                tableDataTd[i].innerHTML = 'O';
                message.innerHTML = `Vez do jogador O`;
            }
            */
        } else {
            alert('Você não pode jogar em uma casa ocupada');
        }
    });
}

function validarItens(x, y ,z) {
    if (x.innerHTML == 'X' && y.innerHTML == 'X' && z.innerHTML == 'X') {
        return 'Jogador X Venceu';
    }

    if (x.innerHTML == 'O' && y.innerHTML == 'O' && z.innerHTML == 'O') {
        return 'Jogador O Venceu';
    }

    return false;
}


