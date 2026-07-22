/* ==========================================
   TechStore
   Programación Web II
========================================== */

const buscador = document.getElementById("buscar");

if (buscador) {

    buscador.addEventListener("keyup", function () {

        let texto = buscador.value.toLowerCase();

        let productos = document.querySelectorAll(".producto");

        productos.forEach(function (producto) {

            let contenido = producto.innerText.toLowerCase();

            if (contenido.includes(texto)) {

                producto.style.display = "block";

            } else {

                producto.style.display = "none";

            }

        });

    });

}