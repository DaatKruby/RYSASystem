const MSJ_ERROR_AJAX = "Hubo un error, intentelo en otro momento";
const PAGE_USER_INFO_FORM = document.getElementById("page-user-info-form").value;
const MAX_TICKET_NUMBER = document.getElementById("max-ticket-number").value;
const TICKET_MAX_DIGITS = MAX_TICKET_NUMBER.length;
const SERVICE_RANDOMS_NUMBERS = document.getElementById("service-random-numbers").value;
const SERVICE_AVAILABLE_TICKETS = document.getElementById("service-available-tickets").value;
const SERVICE_CHECK_NUMBER_STATUS = document.getElementById("service-check-number-status").value;
const CANT_TICKETS_ALLOWED = document.getElementById("cant-tickets-allowed").value.split(",");
const MOSTRAR_OCUPADOS_CUADRICULA = document.getElementById("mostrar-ocupados-cuadricula").value;
const COMIENZO_NUMEROS_RANDOM = document.getElementById("comienzo-numeros-randoms").value;

/*Desactivar todos los botones de verificar de los forms*/
const formVerificarButtons = document.querySelectorAll(".btn-verificar-general");
formVerificarButtons.forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault();
    });
});

/*Agregar max lenght a inputs*/
const inputsTicketNumber = document.querySelectorAll(".ticket-input-number");
inputsTicketNumber.forEach(input => {
    input.setAttribute('maxlength', TICKET_MAX_DIGITS);
});

function obtenerInputsDeForm(formElement) {
    const formInputs = [];
    var formInputsRaw = formElement.querySelectorAll('input');
    for (var i = 0; i < formInputsRaw.length; i++) {
        if (formInputsRaw[i].type.toLowerCase() == 'text' && formInputsRaw[i].className != "verificado") {
            formInputs.push(formInputsRaw[i]);
        }
    }
    return formInputs;
}

function llenarInputsVacios(inputs, randomNumbers) {
    let numberIndex = 0;
    inputs.forEach(input => {
        if (input.value.length === 0) {
            input.value = randomNumbers[numberIndex];
            numberIndex++;
        }
    });
}

async function rellenarFormConRandoms(element) {
    try {
        const elementForm = element.parentElement.parentElement.parentElement.parentElement;
        const formInputs = obtenerInputsDeForm(elementForm);
        let cantNumerosRandoms = 0;

        formInputs.forEach(input => {
            if (input.value.length === 0) {
                cantNumerosRandoms++;
            }
        });

        const url = SERVICE_RANDOMS_NUMBERS + '&cantidad=' + cantNumerosRandoms;
        var fetchHeaders = new Headers();
        fetchHeaders.append('pragma', 'no-cache');
        fetchHeaders.append('cache-control', 'no-cache');

        var fetchConfig = {
            method: 'GET',
            headers: fetchHeaders,
        };

        let resultadoFetch = await fetch(url, fetchConfig);
        let boletosStr = await resultadoFetch.text();
        let boletosRND = boletosStr.split(",");

        llenarInputsVacios(formInputs, boletosRND);
    } catch (error) {
        alert("Hubo un error, intentelo de nuevo");
    }
}

function toggleBotonContinuarGeneral(elementoHTML, mostrar, verificado) {
    const compHR = "<hr class='mt-4 mb-3'>";
    const compP = "<p class='pb-2 pt-0 m-0 text-center'>¡Todo en orden para continuar!</p>";
    const compInput = "<input type='submit' onclick='disableBtnOnClick(this)' class='btn btn-success form-control' value='¡Apartar Boleto!'>";

    verificado.value = false;
    elementoHTML.innerHTML = "";
    if (mostrar) {
        elementoHTML.innerHTML = compHR + compP + compInput;
        verificado.value = true;
    }
}

function agregarMsjErrorEnInputs(contenedoresMsj, inputs, posErrores, msj) {
    contenedoresMsj.forEach((contenedor, key) => {
        contenedor.innerHTML = "";
        inputs[key].style.color = "#28303d";
        if (posErrores[key]) {
            contenedor.innerHTML = msj;
            inputs[key].style.color = "#FF0000";
        }
    });
}

function agregarMsjOcupadoGeneral(estadosBoleto, inputs, contenedoresMsj) {
    var canProceed = true;
    estadosBoleto.forEach((estado) => {
        if (estado) {
            canProceed = false;
        }
    });
    agregarMsjErrorEnInputs(contenedoresMsj, inputs, estadosBoleto, "¡El boleto está ocupado! &#128071;");
    return canProceed;
}

function agregarMsjBoletosRepetidos(boletos, inputs, contenedoresMsj) {
    var canProceed = true;
    let repeticion;
    let posicionesRepetidas = [];
    boletos.forEach(boleto => {
        repeticion = boletos.filter(boletoComparar => boletoComparar === boleto).length;
        if (repeticion != 1) {
            canProceed = false;
            posicionesRepetidas.push(true);
        } else {
            posicionesRepetidas.push(false);
        }
    });

    agregarMsjErrorEnInputs(contenedoresMsj, inputs, posicionesRepetidas, "¡Solo puede escoger boletos diferentes! &#128071;");
    return canProceed;
}

function inputOnChange(input) {
    const elementForm = input.parentElement.parentElement;
    const formInputs = elementForm.querySelectorAll(".drop-list-input");
    const datosVerificados = elementForm.querySelectorAll('.verificado')[0];
    const containerButtonSubmit = elementForm.querySelectorAll(".container-resultado")[0];

    if (datosVerificados.value == "true") {
        toggleBotonContinuarGeneral(containerButtonSubmit, false, datosVerificados);
        datosVerificados.value = false;
    }
}

function verificarNumerosDebajoDeRandom(boletos, inputs, contenedoresMsj) {
    var canProceed = true;
    let posicionesErroneas = [];
    boletos.forEach(boleto => {
        boleto = parseInt(boleto);
        if (boleto >= COMIENZO_NUMEROS_RANDOM) {
            canProceed = false;
            posicionesErroneas.push(true);
        } else {
            posicionesErroneas.push(false);
        }
    });

    agregarMsjErrorEnInputs(contenedoresMsj, inputs, posicionesErroneas, "¡Ese numero no es valido! &#128071;");
    return canProceed;
}

async function verificarForm(element) {
    const btnVerificar = element;
    try {
        const elementForm = btnVerificar.parentElement.parentElement;
        const formInputs = obtenerInputsDeForm(elementForm);
        const datosVerificados = elementForm.querySelectorAll('.verificado')[0];
        const errorMsjContainers = elementForm.querySelectorAll(".cont-msj-ocupado small em");
        const containerButtonSubmit = elementForm.querySelectorAll(".container-resultado")[0];

        let boletos = "";
        let boletosArr = [];

        agregarCerosToInputs(formInputs);
        toggleBotonContinuarGeneral(containerButtonSubmit, false, datosVerificados);
        btnVerificar.disabled = true;

        formInputs.forEach((input, key) => {
            boletos += input.value;
            boletosArr.push(input.value);
            if (key != formInputs.length - 1) {
                boletos += ",";
            }
        });

        proceder = agregarMsjBoletosRepetidos(boletosArr, formInputs, errorMsjContainers);
        if (!proceder) {
            datosVerificados.value = false;
            btnVerificar.disabled = false;
            return;
        }
        proceder = verificarNumerosDebajoDeRandom(boletosArr, formInputs, errorMsjContainers);
        if (!proceder) {
            datosVerificados.value = false;
            btnVerificar.disabled = false;
            return;
        }

        const url = SERVICE_CHECK_NUMBER_STATUS + '&numbers=' + boletos;
        var fetchHeaders = new Headers();
        fetchHeaders.append('pragma', 'no-cache');
        fetchHeaders.append('cache-control', 'no-cache');

        var fetchConfig = {
            method: 'GET',
            headers: fetchHeaders,
        };
        let resultadoFetch = await fetch(url, fetchConfig);
        let estadosStr = await resultadoFetch.text();
        let estadoBoletos = estadosStr.split(",");

        estadoBoletos = convertirEstadoToBoolean(estadoBoletos);
        var canProceed = agregarMsjOcupadoGeneral(estadoBoletos, formInputs, errorMsjContainers);

        if (canProceed) {
            toggleBotonContinuarGeneral(containerButtonSubmit, true, datosVerificados);
        }
    } catch (error) {
        alert(MSJ_ERROR_AJAX);
    }
    btnVerificar.disabled = false;
}

function convertirEstadoToBoolean(estados) {
    let posErrores = [];
    estados.forEach(estado => {
        if (estado == 0) posErrores.push(true);
        else posErrores.push(false);
    });
    return posErrores;
}

function agregarCerosToInputs(inputs) {
    inputs.forEach(input => {
        input.value = pad(input.value, TICKET_MAX_DIGITS);
    });
}

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function disableBtnOnClick(btn) {
    btn.disable = true;
}

/*CODIGO SCROLL*/
const goTopBotton = document.getElementById("back-to-top");
let scroll_detect_bloqueado = false;

window.onscroll = function () {
    if (!scroll_detect_bloqueado) {
        scrollFunction();
        scroll_detect_bloqueado = true;
        setTimeout(function () { scroll_detect_bloqueado = false; }, 300);
    }
};

function scrollFunction() {
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
        goTopBotton.style.display = "block";
    } else {
        goTopBotton.style.display = "none";
    }
}

/*BTN FORM RANDOM*/
async function verificarRandomForm(element, event) {
    event.preventDefault();
    const cantNumerosRandoms = parseInt(element.querySelectorAll(".cant-randoms")[0].value);
    const inputArea = element.querySelectorAll(".rnd-input-area")[0];
    const inputBtn = element.querySelectorAll(".input-btn-rnd")[0];
    inputBtn.disabled = true;

    const url = SERVICE_RANDOMS_NUMBERS + '&cantidad=' + cantNumerosRandoms;
    var fetchHeaders = new Headers();
    fetchHeaders.append('pragma', 'no-cache');
    fetchHeaders.append('cache-control', 'no-cache');

    var fetchConfig = {
        method: 'GET',
        headers: fetchHeaders,
    };

    try {
        let resultadoFetch = await fetch(url, fetchConfig);
        let boletosStr = await resultadoFetch.text();
        let boletos = boletosStr.split(",");

        const urlChecarBoletos = SERVICE_CHECK_NUMBER_STATUS + '&numbers=' + boletosStr;
        resultadoFetch = await fetch(urlChecarBoletos, fetchConfig);
        let estadosBoleto = await resultadoFetch.text();
        estadosBoleto = await estadosBoleto.split(",");
        estadosBoleto = convertirEstadoToBoolean(estadosBoleto);

        let boletoOcupado = false;
        estadosBoleto.forEach((estado) => {
            if (estado) {
                boletoOcupado = true;
            }
        });

        if (!boletoOcupado) {
            agregarInputsRndInvisibles(inputArea, boletos);
            element.submit();
        } else {
            alert("Al parecer ya no quedan suficientes boletos disponibles, pruebe seleccionando otra opcion");
        }

    } catch (error) {
        alert(MSJ_ERROR_AJAX);
    }
    inputBtn.disabled = false;
}

function agregarInputsRndInvisibles(contenedor, numeros) {
    contenedor.innerHTML = "";
    numeros.forEach(numero => {
        var input = document.createElement('input');
        input.type = 'text';
        input.hidden = true;
        input.name = "number[]"
        input.value = numero;

        contenedor.appendChild(input);
    });
}