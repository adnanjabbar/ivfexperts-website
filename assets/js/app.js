document.addEventListener("DOMContentLoaded", function(){

    console.log("WHO6 Engine Active");

    function updateWHOEngine() {

        let volume = parseFloat(document.getElementById("volume")?.value) || 0;
        let concentration = parseFloat(document.getElementById("concentration")?.value) || 0;
        let progressive = parseFloat(document.getElementById("progressive")?.value) || 0;
        let nonprog = parseFloat(document.getElementById("non_progressive")?.value) || 0;
        let morphology = parseFloat(document.getElementById("morphology")?.value) || 0;

        /* CALCULATIONS */

        let totalCount = volume * concentration;
        let totalMotility = progressive + nonprog;
        let immotile = 100 - totalMotility;
        if (immotile < 0) immotile = 0;

        if (document.getElementById("total_count"))
            document.getElementById("total_count").value = totalCount.toFixed(2);

        if (document.getElementById("total_motility"))
            document.getElementById("total_motility").value = totalMotility.toFixed(2);

        if (document.getElementById("immotile"))
            document.getElementById("immotile").value = immotile.toFixed(2);

        /* COLORING */

        colorField("volume", volume, 1.4);
        colorField("concentration", concentration, 16);
        colorField("progressive", progressive, 30);
        colorField("morphology", morphology, 4);

        /* LIVE DIAGNOSIS */

        generateLiveDiagnosis(volume, concentration, progressive, morphology);
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

    function generateLiveDiagnosis(volume, concentration, progressive, morphology){

        let diagnosis = "Normozoospermia";

        if(concentration <= 0){
            diagnosis = "Azoospermia";
        }
        else {

            let flags = [];

            if(volume < 1.4){
                flags.push("Hypospermia");
            }

            if(concentration < 16){
                if(concentration < 5){
                    flags.push("Severe Oligozoospermia");
                }
                else if(concentration < 10){
                    flags.push("Moderate Oligozoospermia");
                }
                else{
                    flags.push("Mild Oligozoospermia");
                }
            }

            if(progressive < 30){
                flags.push("Asthenozoospermia");
            }

            if(morphology < 4){
                flags.push("Teratozoospermia");
            }

            if(flags.length === 0){
                diagnosis = "Normozoospermia";
            } else {
                diagnosis = flags.join(", ");
            }
        }

        let box = document.getElementById("diagnosisText");
        if(box){
            box.innerHTML = diagnosis;
        }
    }

    /* BIND EVENTS AFTER DOM LOAD */

    ["volume","concentration","progressive","non_progressive","morphology"]
    .forEach(id=>{
        let el = document.getElementById(id);
        if(el){
            el.addEventListener("input", updateWHOEngine);
        }
    });

});