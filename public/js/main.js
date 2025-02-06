import {Fetch} from "./files/connector.js";
import {
    commentValidator,
    createValidator,
    editValidator,
    loginValidator,
    registerValidator, updateProfileValidator
} from "./files/forms/validators.js";
import {formDataCollector} from "./files/forms/form-data-colletor.js";

Fetch.setApiUrl("/api/v1/");

// edit(update) book
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

// delete book
document.querySelector('button#delete')?.addEventListener("click", async (e) => {
    e.preventDefault();
    if (!confirm('Naozaj chceš zmazať túto knihu?'))
        return;
    await Fetch.delete(e.currentTarget.dataset.id);
    window.location.href = '/';
});

// login
formDataCollector(
    document.querySelector('form#login'),
    'login:post', loginValidator,
    (form, result) => {
        console.log(document.cookie);
        if (result.success)
            window.location.href = '/panel/profil';
    });

//register (create user)
formDataCollector(
    document.querySelector('form#register'),
    'register:post', registerValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/';
    });

//edit(update) profile
const userId = document.querySelector('input[name=id]')?.value;
formDataCollector(
    document.querySelector('form#editProf'),
    'profil/'+ userId + ':put', updateProfileValidator,
    (form, result) => {
        if (result.success){
            window.location.href = '/panel/profil';
        }
    });

//delete profile
document.querySelector('button#deleteProfile')?.addEventListener("click", async (e) => {
    e.preventDefault();

    if (!confirm('Naozaj chceš zmazať tento profil?'))
        return;

    // Získanie ID používateľa
    const userId = e.currentTarget.dataset.id;

    const response = await Fetch.delete(`/panel/profil/${userId}`);

    // Spracovanie odpovede
    if (response.success) {
        window.location.href = '/logout'; // Po úspešnom odstránení, odhlás sa
    } else {
        alert('Nastala chyba pri odstraňovaní profilu.');
    }
});


//edit(update) comment
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.editCommentButton').forEach(button => {
        button.addEventListener('click', (e) => {
            const form = e.target.closest('div').querySelector('.editCommentForm');
            if (form) {
                form.style.display = 'block';

                if (!form.dataset.listenerAdded) {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();

                        const commentId = form.querySelector('[name="comment_id"]').value;
                        formDataCollector(form, `/comments/${commentId}:post`, commentValidator,
                            (form, result) => {
                                if (result.success) {
                                    form.style.display = 'none';
                                    location.reload();
                                } else {
                                    alert('Nastala chyba pri úprave komentára');
                                }
                            });
                    });
                    form.dataset.listenerAdded = "true"; // Prevent duplicate event listeners
                }
            }
        });
    });
});



//create comment
const commentForm = document.querySelector("#commentForm");
if (commentForm) {
    commentForm.addEventListener("submit", async function (e) {
    e.preventDefault(); // Zabráni reloadu stránky

    let form = new FormData(this); // Načíta dáta z formulára

    let comment = form.get('comment').trim();
    if (!comment) {
        alert("Komentár nemôže byť prázdny.");
        return;
    }

    let data = {
        comment: form.get('comment'),
        book_id: form.get('book_id'),
        user_id: form.get('user_id')
    };

    //Získame hodnoty z `data` a pošleme ich priamo (bez zabalenej štruktúry `data`)

    try {
        let result = await Fetch.post("/comments", {
            comment: data.comment,
            book_id: data.book_id,
            user_id: data.user_id
        });  // Odosielame priamo objekt
        if (result && result.success) {
            //alert("Komentár bol pridaný!");
            window.location.reload();
        } else {
            alert("Chyba: " + (result ? result.message : "Nastala chyba pri odosielaní komentára."));
        }
    } catch (error) {
        console.error("Error during comment submission:", error);
        alert("Došlo k chybe pri odosielaní komentára.");
    }
});
} else {
    console.log("Formulár pre komentáre na tejto stránke neexistuje.");
}

//delete comment
document.querySelectorAll('button.deleteComment').forEach(button => {
    button.addEventListener("click", async (e) => {
        e.preventDefault();

        const commentId = e.currentTarget.dataset.id;

        if (!confirm('Naozaj chceš zmazať tento komentár?')) return;

        const response = await Fetch.delete(`/comments/${commentId}`);
        console.log("Response:", response); // Debugging

        if (response.success) {
            window.location.reload();
        } else {
            alert("Chyba pri mazaní komentára.");
        }
    });
});
