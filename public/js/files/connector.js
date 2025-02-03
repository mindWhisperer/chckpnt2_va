export class Fetch {

    static #apiUrl = '';

    static setApiUrl(url) {
        if (!url?.trim?.())
            throw new Error('default api url must be provided');
        this.#apiUrl = url;
    }

    static get(endpoint) {
        return this.#fetch(endpoint);
    }

    static post(endpoint, data) {
        return this.#fetch(endpoint, data, "POST");
    }

    static options(endpoint) {
        return this.#fetch(endpoint, null, "OPTIONS");
    }

    static put(endpoint, data) {
        return this.#fetch(endpoint, data, "PUT");
    }

    static delete(endpoint) {
        return this.#fetch(endpoint, null, "DELETE");
    }

    static async #fetch(url, data = null, method = "GET") {

        if (url === undefined)
            throw new Error('fetch url must be provided.');

        const headers = {
            "Content-Type": "application/json",
        };

        return await fetch(this.#apiUrl + url, {
            method,
            body: JSON.stringify({data: data || {}}),
            headers,
        }).then(response => response.json());
    }
}

