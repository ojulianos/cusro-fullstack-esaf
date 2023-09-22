let jogador = 0;
let tabuleiro_td = document.querySelectorAll('#tabuleiro td');
let tabuleiro_tr = document.querySelectorAll('#tabuleiro tr');
let mensagens = document.querySelector('#mensagens');

function inicio() {
    for (item of tabuleiro_td) {
        item.innerHTML = '_';
        item.removeAttribute('class');
    }

    mensagens.innerHTML = "Para iniciar o jogo clique no botão abaixo";
    mensagens.removeAttribute('class');
    jogador = 0;
    validarJogada();
}

function validarJogada() {
    for (cedula of tabuleiro_td) {
        cedula.addEventListener('click', function(item){
            if (this.innerHTML === '-') {
                alert('Você deve iniciar o jogo primeiro');
                return;
            }
    
            if (this.innerHTML === '_') {
                if (jogador === 0) {
                    this.innerHTML = 'X';
                    this.classList.add('x');
                    jogador = 1;
                } else {
                    this.innerHTML = 'O';
                    this.classList.add('o');
                    jogador = 0;
                }
                
                checarResultado();
                mensagemJogador();
            } else {
                alert('Posição já ocupada');
            }
        });
    }
    mensagemJogador();
}

function mensagemJogador() {
    let jogadorSimbolo = jogador === 0 ? 'X' : 'O';
    mensagens.removeAttribute('class');
    mensagens.innerHTML = `Vez do jogador ${jogadorSimbolo}`;
    mensagens.classList.add(jogadorSimbolo);
}

function checarResultado() {
    let v_x = 0;
    let v_o = 0;
    let total = 0;
    let colunas = [[null, null, null], [null, null, null], [null, null, null]];

    // validar linhas
    for (let i=0; i < tabuleiro_tr.length; i++) {
        let tr = tabuleiro_tr[i];
        v_x = 0;
        v_o = 0;
        total = 0;

        linhas = tr.children;
        for (let j=0; j < linhas.length; j++) {
            let linha = linhas[j];
            if (linha.innerHTML === 'X') {
                v_x++;
            } else if (linha.innerHTML === 'O') {
                v_o++;
            }
            colunas[i][j] = linha;
        }
        
        // validar colunas
        for (let j=0; j < colunas.length; j++) {
            let coluna = colunas[j];
            if (coluna.innerHTML === 'X') {
                v_x++;
            } else if (coluna.innerHTML === 'O') {
                v_o++;
            }
        }

        // validar diagonal 1
    
        
        // validar diagonal 2
        
    }
    console.log(v_x, v_o, total);
    validarVencedor(v_x, v_x, total);
}


function fimJogo(jogador) {
    if (jogador !== 'X' || jogador !== 'O') {
        mensagens.innerHTML = `JOGO TERMINADO EM EMPATE`;
        return;
    }
    let jogadorSimbolo = jogador === 'X' ? 'X' : 'O';
    mensagens.removeAttribute('class');
    mensagens.innerHTML = `Jogador ${jogadorSimbolo} VENCEU`;
    mensagens.classList.add(jogadorSimbolo);
}

function validarVencedor(x, o, total) {
    if (x === 3) {
        return fimJogo('X');
    } else if (o === 3) {
        return fimJogo('O')
    } else if (total === 9) {
        return fimJogo('_')
    }
}
