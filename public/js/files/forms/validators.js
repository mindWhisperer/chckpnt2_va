import {isValidUrl} from "../helpers.js";
/**
 * @param {{email: string, password: string}} data
 * @returns {[string, string][]}
 */
export const loginValidator = (data) => {
    /** @type {[string, string][]} */
    const errors = [];
    if (!data?.email || !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]{2,}$/g.test(data.email)) {
        errors.push(["email", "Nesprávny tvar prihlasovacieho emailu."]);
    }
    if (!data?.password?.trim?.()) {
        errors.push(["password", "Musíš zadať heslo."]);
    } else if (data.password.trim().length < 3) {
        errors.push(["password", "Heslo musí mať aspoň 3 znaky."]);
    }
    return errors;
};

/**
 * @param {{id:string, name:string, description:string, image:string, genre: string|int}} data
 * @returns {[string, string][]}
 */
export const editValidator = (data) => {
    /** @type {[string, string][]} */
    const errors = [];
    if (!data?.name?.trim?.()) {
        errors.push(['name', 'Názov knihy nesmie byť prázdny.']);
    }
    if (!data?.description?.trim?.()) {
        errors.push(['description', 'Chýba popis knihy.']);
    }
    if (!data?.image?.trim?.()) {
        errors.push(['image', 'Nebol nastavený obrázok.']);
    } else if (!isValidUrl(data.image)) {
        errors.push(['image', 'Url obrázku nemá správny tvar.']);
    }
    if (!data?.genre?.trim?.()) {
        errors.push(['genre', 'Nebol vybraný žáner.']);
    }
    return errors;
};
export const createValidator = editValidator;
