import {Fetch} from "../connector.js";

/**
 * @param {HTMLFormElement} form
 * @param {string} endpoint 1. "endpoint" 2. "endpoint:method" ("login:post") ("register:post")
 * @param {({[p: string]: string}) => [string, string][]} validator
 * @param {(form:HTMLFormElement, {[p: string]: unknown, success: boolean, errors?: [string, string][], code?: number, message?: string, Authorization?: string}) => void} callback
 * @returns {void}
 */
export const formDataCollector = (form, endpoint, validator, callback) => {


    if (!form)
        return;

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        const validationResult = validator?.(data);
        if (validationResult?.length) {
            const result = {errors: validationResult, success: false};
            if (formErrorsRenderer(form, result))
                return;
            return callback?.(form, result);
        }

        const [_endpoint, _method] = endpoint.split(':');

        Fetch[_method?.toLowerCase?.() || "post"](_endpoint, data).then(response => {

            if (formErrorsRenderer(form, response))
                return;
            if (response.success)
                form.reset();
            callback?.(form, response);
        })
    });
}

/**
 * @param {HTMLFormElement} form
 * @param {{[p: string]: unknown, success: boolean, errors?: [string, string][], code?: number, message?: string, Authorization?: string}} result
 * @returns {boolean}
 */
export const formErrorsRenderer = (form, result) => {
    const errorContainer = form.querySelector('div.form-error-container');
    if (errorContainer) {
        errorContainer.innerHTML = '';
        errorContainer.classList.add('d-none'); //Skrytie kontajneru
        errorContainer.style.padding = '0'; // Odstránenie paddingu pri skrytí
    }

    const errors = result?.errors;
    if (result.success || !errors)
        return false;

    if (errorContainer) {
        errorContainer.classList.remove('d-none'); // Zobrazenie kontajnera
        errorContainer.style.paddingTop = '1rem'; // Nastaviť padding hore iba keď je viditeľný
        errorContainer.style.paddingLeft = '1rem'; // Nastaviť padding vľavo iba keď je viditeľný


        // Pridanie nadpisu pre chyby
        const header = document.createElement('p');
        header.textContent = 'Formulár obsahuje nasledovné chyby:';
        header.classList.add('fw-bold'); // Bootstrap trieda pre tučné písmo
        errorContainer.appendChild(header);

        // Vytvorenie zoznamu chýb
        const ul = document.createElement('ul');
        errors.forEach(([name, value]) => {
            const li = document.createElement('li');
            li.textContent = value; // Bezpečne pridanie textu
            ul.appendChild(li);
        });
        errorContainer.appendChild(ul);
    }
    else
        result.errors.forEach(([name, value]) => console.warn(value));

    return true;
}
