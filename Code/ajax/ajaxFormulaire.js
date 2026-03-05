const heureDebut = document.getElementById("heuredebut");
const heureFin = document.getElementById("heurefin");
const debut = document.getElementById("debut");
const final = document.getElementById("fin");
const motif = document.getElementById("motif")
let valider = document.getElementById("submit")




debut.addEventListener("input", function () {
    if (debut.value.trim() !== "") {
        final.disabled = false;
        heureDebut.disabled = false;
        heureFin.disabled = false;
        let message = document.getElementById("erreur1")
        message.style.display = "none";
    } else {
        final.disabled = true;
        heureDebut.disabled = false;
        heureFin.disabled = false;
    }
});

heureDebut.addEventListener("click",function (){
    if(debut.value === ""){
        heureDebut.disabled = true;
        let message = document.getElementById("erreur1")
        message.style.display = "block";
    }
})

final.addEventListener("input", function () {
    if (final.value.trim() === "") {
        heureFin.disabled = true;
    } else {
        heureFin.disabled = false;
        let message = document.getElementById("erreur3")
        message.style.display = "none";
    }
});

heureFin.addEventListener("click",function (){
    if(final.value === ""){
        heureFin.disabled = true;
        let message = document.getElementById("erreur3")
        message.style.display = "block";
    }
})

function verifierDateHeure() {
    const dateDebut = document.getElementById("debut").value;
    const heureDebut = document.getElementById("heuredebut").value;

    const dateFin = document.getElementById("fin").value;
    const heureFin = document.getElementById("heurefin").value;

    const debutDateTime = new Date(dateDebut + "T" + heureDebut);
    const finDateTime = new Date(dateFin + "T" + heureFin);


    if (finDateTime < debutDateTime) {
        document.getElementById("erreur4").style.display = "block";
    } else {
        document.getElementById("erreur4").style.display = "none";
    }
}

heureFin.addEventListener("change",verifierDateHeure);

