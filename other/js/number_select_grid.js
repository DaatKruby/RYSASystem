//CODIGO CUADRICULA

let numbersSelected = [];
const TICKET_COUNT_ALLOWED = CANT_TICKETS_ALLOWED;
const MAX_TICKET_QUANTITY = Math.max(...TICKET_COUNT_ALLOWED);
let listaBoletosDisponibles = cantidadBoletosToSelectGridToString();
const SELECTED_TICKET_CONTAINER = document.getElementById("boletos-user-cont");
const boletos_por_pagina = parseInt(document.getElementById("boletos-por-pagina").value);
const GRID_CONTAINER = document.getElementById("grid-container");
const CLICK_GRID_MSJ = document.getElementById("confirm-click-msj");
const CLICK_GRID_MSJ_REMOVE = document.getElementById("confirm-click-msj-remove");
let timeOutGridClickMsj = null;
let timeOutGridClickMsjRemove = null;

let gridMinRng = 0;
let gridMaxRng = boletos_por_pagina;
const gridMinRngElement = document.getElementById("grid-rng-min");
const gridMaxRngElement = document.getElementById("grid-rng-max");
let gridLoading = false;
const loadingAnimContainer = document.getElementById("loading-animation");
let loadingAlCargarPagina = true;

loadGrid(gridMinRng, gridMaxRng);
if (isSeleccionGridActiva()) {
    renderSelectedTicketsContainer();
}

function renderSelectedTicketsContainer() {
    SELECTED_TICKET_CONTAINER.innerHTML = "";
    if (numbersSelected.length > 0) {
        numbersSelected.forEach(numberSelected => {
            addNumberToDivContainer(numberSelected);
        });
    } else {
        const message = document.createElement("p");
        message.classList.add("h5");
        message.classList.add("text-muted");
        message.classList.add("font-italic");
        message.innerHTML = "No ha seleccionado ningun boleto"
        SELECTED_TICKET_CONTAINER.appendChild(message);
    }
}

function activeLoadingAnimation(active) {
    if (active) {
        loadingAnimContainer.classList.remove("hide");
    } else {
        loadingAnimContainer.classList.add("hide");
    }
}

async function gridContinue() {
    try {
        let form_link = PAGE_USER_INFO_FORM;
        let continuar = false;
        TICKET_COUNT_ALLOWED.forEach((ticketQuantity) => {
            if (numbersSelected.length == ticketQuantity) {
                continuar = true;
            }
        });

        if (!continuar) {
            alert(`Debe seleccionar ${listaBoletosDisponibles} boletos \nUsted selecciono ${numbersSelected.length}`);
            return;
        }

        /*CHECAR ESTADOS BOLETOS*/
        let boletosStr = "";
        numbersSelected.forEach((numero, index) => {
            boletosStr += numero;
            if (index != numbersSelected.length - 1) {
                boletosStr += ",";
            }
        });

        const url = SERVICE_CHECK_NUMBER_STATUS + '&numbers=' + boletosStr;
        var fetchHeaders = new Headers();
        fetchHeaders.append('pragma', 'no-cache');
        fetchHeaders.append('cache-control', 'no-cache');

        var fetchConfig = {
            method: 'GET',
            headers: fetchHeaders,
        };
        const resultadoFetch = await fetch(url, fetchConfig);
        let estadosBoleto = await resultadoFetch.text();
        estadosBoleto = await estadosBoleto.split(",");
        estadosBoleto = convertirEstadoToBoolean(estadosBoleto);

        let boletosOcupados = [];
        estadosBoleto.forEach((estado, index) => {
            if (estado) {
                boletosOcupados.push(numbersSelected[index]);
            }
        });

        if (boletosOcupados.length != 0) {
            const msjBoletosOcupados = `Algunos boletos seleccionados acaban de ser apartados:\nOcupados: ${boletosOcupados}`;
            alert(msjBoletosOcupados);
            loadGrid(gridMinRng, gridMaxRng);
            return;
        }

        /*HACER POST*/
        const form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", form_link);
        form.classList.add("d-none");

        let inputNumber;
        numbersSelected.forEach((element) => {
            inputNumber = document.createElement("input");
            inputNumber.setAttribute("type", "text");
            inputNumber.setAttribute("name", "number[]");
            inputNumber.value = element;
            form.appendChild(inputNumber);
        });

        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        alert(MSJ_ERROR_AJAX);
    }
}

function cantidadBoletosToSelectGridToString() {
    let cantidades = "";
    TICKET_COUNT_ALLOWED.forEach((cantidad, index) => {
        cantidades += cantidad;
        if (index != TICKET_COUNT_ALLOWED.length - 1) {
            cantidades += ", ";
        }
    });
    return cantidades;
}

function numberButtonPress(event) {
    const btnElement = event.target;
    if (btnElement.tagName.toUpperCase() != "BUTTON" || btnElement.disabled) {
        return;
    };
    const number = btnElement.value;
    const isNumberSelected = numbersSelected.find(element => element == number);
    const indexOfSelectedNumber = numbersSelected.indexOf(number);

    if (isNumberSelected) {
        numbersSelected.splice(indexOfSelectedNumber, 1);
        btnElement.classList.remove("btn-number-press");
        showMensajeConfirmacionClickGrid(false);
        renderSelectedTicketsContainer();
    } else {
        if (numbersSelected.length < MAX_TICKET_QUANTITY) {
            btnElement.classList.add("btn-number-press");
            numbersSelected.push(number);
            renderSelectedTicketsContainer();
            showMensajeConfirmacionClickGrid(true);
        } else {
            alert("Ya ha seleccionado la cantidad maxima posible");
        }
    }
}

function isSeleccionGridActiva() {
    return SELECTED_TICKET_CONTAINER != null;
}

function addNumberToDivContainer(number) {
    const numberElement = document.createElement("div");
    numberElement.classList.add("number-selected-element");
    const spam = document.createElement("spam");
    spam.classList.add("m-0");
    spam.classList.add("p-0");
    spam.append(number);
    numberElement.appendChild(spam);
    numberElement.setAttribute("onclick", "removeSelectedTicket(this);");

    SELECTED_TICKET_CONTAINER.appendChild(numberElement);
}

function removeSelectedTicket(element) {
    const number = element.firstChild.innerHTML;
    const index = numbersSelected.indexOf(number);
    numbersSelected.splice(index, 1);

    renderSelectedTicketsContainer();

    gridElement = buscarElementoGrid(parseInt(number));
    if (gridElement) {
        gridElement.classList.remove("btn-number-press");
    }
}

async function loadGrid(min, max) {
    gridLoading = true;

    if (loadingAlCargarPagina) {
        loadingAlCargarPagina = false;
    } else {
        activeLoadingAnimation(true);
    }

    const url = SERVICE_AVAILABLE_TICKETS + "&min=" + min + "&max=" + max;
    var fetchHeaders = new Headers();
    fetchHeaders.append('pragma', 'no-cache');
    fetchHeaders.append('cache-control', 'no-cache');

    var fetchConfig = {
        method: 'GET',
        headers: fetchHeaders,
    };
    gridMinRngElement.innerHTML = pad(min, TICKET_MAX_DIGITS);
    gridMaxRngElement.innerHTML = pad(max, TICKET_MAX_DIGITS);
    try {
        let resultadoFetch = await fetch(url, fetchConfig);
        let result = await resultadoFetch.json();

        if (result.error) {
            alert(MSJ_ERROR_AJAX);
        } else {
            GRID_CONTAINER.innerHTML = "";
            let boletos = result.boletos;

            if (boletos.length === 0) {
                addGridEmptyMessage();
            } else {
                let componente;
                if (MOSTRAR_OCUPADOS_CUADRICULA == 1) {
                    boletos.forEach(boleto => {
                        if (boleto.estado === "0") {
                            componente = createGridBtn(pad(boleto.numero, TICKET_MAX_DIGITS));
                        } else {
                            componente = createGridBtnOcupado(pad(boleto.numero, TICKET_MAX_DIGITS));
                        }
                        GRID_CONTAINER.appendChild(componente);
                    });
                } else {
                    boletos.forEach(boleto => {
                        if (boleto.estado === "0") {
                            componente = createGridBtn(pad(boleto.numero, TICKET_MAX_DIGITS));
                            GRID_CONTAINER.appendChild(componente);
                        }
                    });
                }
                actualizarGridBoletosSeleccionados();
                if (GRID_CONTAINER.firstChild == null) {
                    addGridEmptyMessage();
                }
            }
        }
    } catch (error) {
        activeLoadingAnimation(false);
        alert(MSJ_ERROR_AJAX);
    }
    activeLoadingAnimation(false);
    gridLoading = false;
}


function addGridEmptyMessage() {
    const container = document.createElement("div");
    const hr = document.createElement("hr");
    hr.classList.add("my-1");
    container.classList.add("text-center", "my-5", "text-secondary");
    const title = document.createElement("p");
    title.innerHTML = "No hay boletos que mostrar";
    title.classList.add("h3");
    const subtitle = document.createElement("p");
    subtitle.innerHTML = "Pruebe viendo las otras paginas (Click en los botones arriba de la cuadricula)";
    subtitle.classList.add("h5");
    container.appendChild(title);
    container.appendChild(hr);
    container.appendChild(subtitle);
    GRID_CONTAINER.appendChild(container);
}

function createGridBtn(number) {
    let btn = document.createElement("button");
    btn.classList.add("grid-button", "btn-grid");
    btn.value = number;
    btn.innerHTML = number;
    return btn;
}

function createGridBtnOcupado(number) {
    let btn = document.createElement("button");
    btn.classList.add("grid-button", "btn-grid", "btn-number-lock");
    btn.disabled = true;
    btn.value = number;
    btn.innerHTML = number;
    return btn;
}

function gridLeft() {
    if (!gridLoading) {
        let aux = gridMinRng - boletos_por_pagina;
        if (aux >= 0) {
            gridMaxRng = gridMinRng;
            gridMinRng = aux;

            loadGrid(gridMinRng, gridMaxRng);
        }
    }
}

function gridRight() {
    if (!gridLoading) {
        gridMinRng = gridMaxRng;
        gridMaxRng += boletos_por_pagina;

        loadGrid(gridMinRng, gridMaxRng);
    }
}

function showMensajeConfirmacionClickGrid(add_ticket) {
    CLICK_GRID_MSJ.classList.add("hide");
    CLICK_GRID_MSJ_REMOVE.classList.add("hide");

    if (timeOutGridClickMsj) {
        clearTimeout(timeOutGridClickMsj);
    }
    if (timeOutGridClickMsjRemove) {
        clearTimeout(timeOutGridClickMsjRemove);
    }

    if (add_ticket) {
        CLICK_GRID_MSJ.classList.remove("hide");
        timeOutGridClickMsj = setTimeout(function () { CLICK_GRID_MSJ.classList.add("hide"); }, 1000);
    } else {
        CLICK_GRID_MSJ_REMOVE.classList.remove("hide");
        timeOutGridClickMsjRemove = setTimeout(function () { CLICK_GRID_MSJ_REMOVE.classList.add("hide"); }, 1000);
    }
}

function actualizarGridBoletosSeleccionados() {
    numbersSelected.forEach(number_selected => {
        element = buscarElementoGrid(parseInt(number_selected));
        if (element) {
            element.classList.add("btn-number-press");
        }
    });
}

function buscarElementoGrid(numero) {
    let grid_components = GRID_CONTAINER.children;
    let start = 0, end = grid_components.length - 1;

    while (start <= end) {

        let mid = Math.floor((start + end) / 2);
        let element = grid_components[mid];
        let number = parseInt(element.innerHTML);

        if (number === numero) return element;

        else if (number < numero)
            start = mid + 1;
        else
            end = mid - 1;
    }
    return null;
}