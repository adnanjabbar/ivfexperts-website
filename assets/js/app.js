document.addEventListener("DOMContentLoaded", function(){

    /* ================= ACCORDION ================= */
    document.querySelectorAll(".accordion-toggle").forEach(btn=>{
        btn.addEventListener("click",()=>{
            let content = btn.nextElementSibling;
            content.style.display = content.style.display === "block" ? "none" : "block";
        });
    });

    /* ================= SEMEN INTELLIGENT ENGINE ================= */

    function updateMotility() {

        let progressive = parseFloat(document.getElementById("progressive")?.value) || 0;
        let nonprog = parseFloat(document.getElementById("non_progressive")?.value) || 0;

        let immotile = 100 - (progressive + nonprog);
        if (immotile < 0) immotile = 0;

        let immField = document.getElementById("immotile");
        let totalField = document.getElementById("total_motility");

        if (immField) immField.value = immotile.toFixed(2);
        if (totalField) totalField.value = (progressive + nonprog).toFixed(2);

        colorField("progressive", progressive, 30);
        colorField("concentration", parseFloat(document.getElementById("concentration")?.value), 16);
        colorField("morphology", parseFloat(document.getElementById("morphology")?.value), 4);
        colorField("volume", parseFloat(document.getElementById("volume")?.value), 1.4);
    }

    function updateTotalCount() {
        let conc = parseFloat(document.getElementById("concentration")?.value) || 0;
        let vol = parseFloat(document.getElementById("volume")?.value) || 0;

        let total = conc * vol;

        let totalField = document.getElementById("total_count");
        if (totalField) totalField.value = total.toFixed(2);
    }

    function colorField(id, value, threshold) {
        let field = document.getElementById(id);
        if (!field || isNaN(value)) return;

        field.classList.remove("border-red-500","border-green-500","text-red-600","font-semibold");

        if (value < threshold) {
            field.classList.add("border-red-500","text-red-600","font-semibold");
        } else {
            field.classList.add("border-green-500");
        }
    }

    // Attach listeners only if semen fields exist
    ["progressive","non_progressive","volume","concentration","morphology"].forEach(id => {
        let el = document.getElementById(id);
        if (el) {
            el.addEventListener("input", function () {
                updateMotility();
                updateTotalCount();
            });
        }
    });

});