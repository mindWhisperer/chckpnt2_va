import {Fetch} from "./files/connector.js";
import {
    commentValidator,
    createValidator,
    editValidator,
    loginValidator,
    registerValidator
} from "./files/forms/validators.js";
import {formDataCollector} from "./files/forms/form-data-colletor.js";

Fetch.setApiUrl("/api/v1/");

// login
formDataCollector(
    document.querySelector('form#login'),
    'login:post', loginValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/';
    });

//register
    formDataCollector(
        document.querySelector('form#register'),
        'register:post', registerValidator,
        (form, result) => {
            if (result.success)
                window.location.href = '/';
        });


// edit
const id = document.querySelector('input[name=id]')?.value;
formDataCollector(
    document.querySelector('form#edit'),
    id + ':put', editValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/detail/' + id;
    });

// create book
formDataCollector(
    document.querySelector('form#create'),
    ':post', createValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/detail/' + result.data.id;
    });

//create comment
/*document.querySelector("#commentForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Zabráni reloadu stránky

    let form = new FormData(this); // Načíta dáta z formulára

    let data = {};
    form.forEach((value, key) => {
        data[key] = value;
    });

    //let result = await Fetch.post("/add-comment", data); // Použitie Fetch.post()
    let result = await Fetch.post("/api/v1/add-comment", data);


    if (result.success) {
        alert("Komentár bol pridaný!");
        window.location.reload();
    } else {
        alert("Chyba: " + result.message);
    }
});*/
formDataCollector(
    document.querySelector('form#commentForm'),
    ':post', commentValidator,
    (form, result) => {
        if (result.success) {
            // Pred odoslaním dát sa môžeš uistiť, že sú správne
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            console.log("Sending comment data:", data);  // Tento log ukáže dáta pred odoslaním

            window.location.reload();
        }
    });




// delete book
document.querySelector('button#delete')?.addEventListener("click", async (e) => {
    e.preventDefault();
    if (!confirm('Naozaj chceš zmazať túto knihu?'))
        return;
    await Fetch.delete(e.currentTarget.dataset.id);
    window.location.href = '/';
});

//delete comment
document.querySelector('button#deleteComment')?.addEventListener("click", async (e) => {
    e.preventDefault();
    // Potvrdenie pred vymazaním
    if (!confirm('Naozaj chceš zmazať tento komentár?'))
        return;
    await Fetch.delete(e.currentTarget.dataset.id);  // Zavoláš API route pre vymazanie komentára
    window.location.reload();
});


//delete profile
document.querySelector('button#deleteProfile')?.addEventListener("click",async (e)=> {
    e.preventDefault();

    if (!confirm('Naozaj chceš zmazať tento profil?'))
        return;

    await Fetch.delete(e.currentTarget.dataset.id);

    window.location.href = '/logout';
})








