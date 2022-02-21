const num_estado = document.getElementById("tk-num-estado").value;
const nombre_estado = document.getElementById("tk-estado-nombre").value;
const folio = document.getElementById("tk-folio").value;
const nombre = document.getElementById("tk-nombre-completo").value;
const boletos = document.getElementById("tk-boletos").value;
const estado_republica = document.getElementById("tk-estado-republica").value;
const fecha_apartado = document.getElementById("tk-fecha-apartado").value;
const url_img_base = document.getElementById("tk-img-base").value;
const MAX_TICKET_DIGITS = document.getElementById("max-ticket-number").value.length;
const cant_boletos_en_img = 5;

if (num_estado !== "null" && num_estado === "2") {
    drawTicket();
}

function drawTicket() {
    const img_base = new Image();
    img_base.crossOrigin = "anonymous";
    img_base.onload = () => {
        const canvas = document.createElement("canvas");
        canvas.width = img_base.width;
        canvas.height = img_base.height;
        const image_container = document.getElementById("ticket-cont");
        var context = canvas.getContext("2d");

        context.drawImage(img_base, 0, 0);
        const base_half_width = img_base.width / 2;

        addTextInfo(context, base_half_width);

        const new_image = new Image();
        new_image.src = canvas.toDataURL();
        image_container.appendChild(new_image);
    };
    img_base.src = url_img_base;
}

function addImgLogoToCanvas(context, base_half_width, img_logo) {
    let new_height = 180;
    let new_width = new_height * img_logo.width / img_logo.height;
    let x = (base_half_width) - (new_width / 2);
    context.drawImage(img_logo, x, 5, new_width, 180);
}

function prepararBoletosParaImg(boletos) {
    let resultado = "";
    let boletos_arr = boletos.split(",");
    let boletos_arr_size = boletos_arr.length;

    for (let index = 0; index < boletos_arr_size && index < cant_boletos_en_img; index++) {
        const boleto = boletos_arr[index];
        resultado += pad(boleto, MAX_TICKET_DIGITS);
        if (index < cant_boletos_en_img - 1 && index < boletos_arr_size - 1) {
            resultado += ", ";
        }
    }

    if (boletos_arr_size > cant_boletos_en_img) {
        resultado += " ...";
    }
    return resultado;
}

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function addTextInfo(context, base_half_width) {
    const txt_separator_group = 55;
    const txt_separator = 32;
    let txt_height = 300;
    const font_title = "bold 32px Arial";
    const font_info = "24px Arial";

    //NOMBRES
    context.textAlign = "center";
    context.font = font_title;
    context.fillText("Nombre", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(nombre, base_half_width, txt_height);

    //BOLETOS
    txt_height += txt_separator_group;
    context.font = font_title;
    context.fillText("Boletos", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(prepararBoletosParaImg(boletos), base_half_width, txt_height);

    //ESTADO DE PAGO
    txt_height += txt_separator_group;
    context.font = font_title;
    context.fillText("Estado de Pago", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(nombre_estado, base_half_width, txt_height);

    //FECHA DE COMPRA
    txt_height += txt_separator_group;
    context.font = font_title;
    context.fillText("Fecha de Compra", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(fecha_apartado, base_half_width, txt_height);

    //ESTADO DE LA REPUBLICA
    txt_height += txt_separator_group;
    context.font = font_title;
    context.fillText("Estado", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(estado_republica, base_half_width, txt_height);

    //FOLIO
    txt_height += txt_separator_group;
    context.font = font_title;
    context.fillText("Folio", base_half_width, txt_height);

    txt_height += txt_separator;
    context.font = font_info;
    context.fillText(folio, base_half_width, txt_height);

}