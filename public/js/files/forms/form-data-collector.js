import {Fetch} from "../connector.js";
/**
 * @param {HTMLFormElement} form
 * @param {string} endpoint 1. "endpoint" 2. "endpoint:method" ("login:post")
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
        Fetch[_method || "post"](_endpoint, data).then(response => {
            if (formErrorsRenderer(form, response))
                return;
            if (response.success)
                form.reset();
            callback?.(form, response);
        });
    });
}
/**
 * @param {HTMLFormElement} form
 * @param {{[p: string]: unknown, success: boolean, errors?: [string, string][], code?: number, message?: string, Authorization?: string}} result
 * @returns {boolean}
 */
export const formErrorsRenderer = (form, result) => {
    const errorContainer = form.querySelector('div.form-error-container');
    if (errorContainer)
        errorContainer.innerHTML = '';
    const errors = result?.errors;
    if (result.success || !errors)
        return false;
    if (errorContainer)
        errorContainer.innerHTML = `<ul>${result.errors.map(([name, value]) => `<li>${value}`).join('')}</ul>`;
    else
        result.errors.forEach(([name, value]) => console.warn(value));
    return true;
}
