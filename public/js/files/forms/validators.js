import {isValidImageUrl} from "../helpers.js";

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
    } else if (data.password.length < 5 ||
        !/[A-Z]/.test(data.password) ||
        !/[0-9]/.test(data.password)) {
        errors.push(["password", "Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo."]);
    }

    return errors;
};

/**
 * @param {{email: string, name: string, password: string, profile_pic:string}} data
 * @returns {[string, string][]}
 */
export const registerValidator = (data) => {
    /** @type {[string, string][]} */
    const errors = [];

    if (!data?.email || !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]{2,}$/g.test(data.email)) {
        errors.push(["email", "Nesprávny tvar prihlasovacieho emailu."]);
    }
    if (!data?.name.trim?.()) {
        errors.push(["name", "Meno nesmie byť prázdne."]);
    }
    if (!data?.password?.trim?.()) {
        errors.push(["password", "Musíš zadať heslo."]);
    } else if (data.password.trim().length < 5 ||
        !/[A-Z]/.test(data.password) ||
        !/[0-9]/.test(data.password)) {
        errors.push(["password", "Heslo musí mať aspoň 5 znakov, jedno veľké písmeno a jedno číslo."]);
    }
    if (data?.profile_pic?.trim?.() && !isValidImageUrl(data.profile_pic)) {
        errors.push(['profile_pic', 'Url obrázku nemá správny tvar.']);
    }

    return errors;
};

export const updateProfileValidator = registerValidator;

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
    if (data?.image?.trim?.() && !isValidImageUrl(data.image)) {
        errors.push(['image', 'Url obrázku nemá správny tvar.']);
    }
    if (!data?.genre?.trim?.()) {
        errors.push(['genre', 'Nebol vybraný žáner.']);
    }
    return errors;
};

export const createValidator = editValidator;

/**
 * @param {{comment:string}} data
 * @returns {[string, string][]}
 */
export const commentValidator = (data) => {
    const errors = [];

    if (!data.comment.trim()) {
        errors.push('Komentár nesmie byť prázdny!');
    }

    if (data.comment.length > 255) {
        errors.push('Komentár môže mať maximálne 255 znakov!');
    }

    return errors;
};
