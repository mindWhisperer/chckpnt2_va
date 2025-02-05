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
document.querySelector("#commentForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Zabráni reloadu stránky

    let form = new FormData(this); // Načíta dáta z formulára

    let data = {
        comment: form.get('comment'),
        book_id: form.get('book_id'),
        user_id: form.get('user_id')
    };

    console.log("Sending comment data:", data);  // Tento log ukáže dáta pred odoslaním

    // Tento krok: Získame hodnoty z `data` a pošleme ich priamo (bez zabalenej štruktúry `data`)

    try {
        let result = await Fetch.post("/comments", {
            comment: data.comment,
            book_id: data.book_id,
            user_id: data.user_id
        });  // Odosielame priamo objekt
        if (result && result.success) {
            alert("Komentár bol pridaný!");
            window.location.reload();
        } else {
            alert("Chyba: " + (result ? result.message : "Nastala chyba pri odosielaní komentára."));
        }
    } catch (error) {
        console.error("Error during comment submission:", error);
        alert("Došlo k chybe pri odosielaní komentára.");
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








