import {Fetch} from "./files/connector.js";
import {createValidator, editValidator, loginValidator, registerValidator} from "./files/forms/validators.js";
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

// create
formDataCollector(
    document.querySelector('form#create'),
    ':post', createValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/detail/' + result.data.id;
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
document.querySelector('button#deleteProfile')?.addEventListener("click", async (e) => {
    e.preventDefault();

    // Potvrdenie pred vymazaním
    if (!confirm('Naozaj chceš zmazať svoj profil?'))
        return;

    await Fetch.delete(e.currentTarget.dataset.id);
    window.location.href = '/';  // Presmerovanie po úspešnom odstránení

});




