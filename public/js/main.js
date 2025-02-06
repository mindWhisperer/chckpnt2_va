import {Fetch} from "./files/connector.js";
import {
    createValidator,
    editValidator,
    loginValidator,
    registerValidator, updateProfileValidator
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


// edit book
const id = document.querySelector('input[name=id]')?.value;
formDataCollector(
    document.querySelector('form#edit'),
    id + ':put', editValidator,
    (form, result) => {
        if (result.success)
            window.location.href = '/detail/' + id;
    });

//edit profile
const userId = document.querySelector('input[name=id]')?.value;

formDataCollector(
    document.querySelector('form#editProf'),
    'profil/'+ userId + ':put', updateProfileValidator,
    (form, result) => {
        const previousPage = sessionStorage.getItem('previousPage');
        if (result.success){
            window.location.href = '/panel/profil';
        }
    });

//edit comment
document.querySelector('#editCommentButton')?.addEventListener('click', (e) => {
    document.querySelector('#editCommentForm').style.display = 'block';

});
document.querySelector('#editCommentForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);

    // Získanie údajov z formulára
    const commentId = formData.get('comment_id');
    const updatedComment = formData.get('comment');

    // Validácia komentára na klientskej strane
    if (!updatedComment.trim()) {
        alert('Komentár nesmie byť prázdny!');
        return;
    }

    if (updatedComment.length > 255) {
        alert('Komentár môže mať maximálne 255 znakov!');
        return;
    }

    // Poslanie PUT požiadavky na API s upraveným komentárom
    const response = await fetch(`/api/v1/comments/${commentId}`, {
        method: 'PUT',
        body: JSON.stringify({
            comment: updatedComment,
            user_id: formData.get('user_id'),
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,  // Ak používate CSRF
        },
    });

    const data = await response.json();

    // Po úspešnom uložení komentára, skryjeme formulár a zaktualizujeme zobrazenie komentára
    if (data.success) {
        document.querySelector('#editCommentForm').style.display = 'none';
        alert('Komentár bol úspešne upravený');
        location.reload(); // Reload stránky, alebo zaktualizuj len komentár na stránke
    } else {
        alert('Nastala chyba pri úprave komentára');
    }
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

    //console.log("Sending comment data:", data);  // Tento log ukáže dáta pred odoslaním

    // Tento krok: Získame hodnoty z `data` a pošleme ich priamo (bez zabalenej štruktúry `data`)

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

// delete book
document.querySelector('button#delete')?.addEventListener("click", async (e) => {
    e.preventDefault();
    if (!confirm('Naozaj chceš zmazať túto knihu?'))
        return;
    await Fetch.delete(e.currentTarget.dataset.id);
    window.location.href = '/';
});

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

//delete profile
document.querySelector('button#deleteProfile')?.addEventListener("click", async (e) => {
    e.preventDefault();

    if (!confirm('Naozaj chceš zmazať tento profil?'))
        return;

    // Získanie ID používateľa
    const userId = e.currentTarget.dataset.id;

    console.log(`/panel/profil/${userId}`);
    // Správna URL pre API
    const response = await Fetch.delete(`/panel/profil/${userId}`);

    // Spracovanie odpovede
    if (response.success) {
        alert('Profil bol úspešne odstránený.');
        window.location.href = '/logout'; // Po úspešnom odstránení, odhlás sa
    } else {
        alert('Nastala chyba pri odstraňovaní profilu.');
    }
});













