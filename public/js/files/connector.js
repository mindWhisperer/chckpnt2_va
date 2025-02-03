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
            // Pridáme CSRF token
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        };

        const options = {
            method,
            body: JSON.stringify({ data: data || {} }), // Ak je potreba, posielame telo
            headers,
        };

        return await fetch(this.#apiUrl + url, options)
            .then(response => response.json())
            .catch(error => console.error("Error deleting comment:", error));  // Pri chybe
    }
}

