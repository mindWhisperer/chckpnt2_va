export class Fetch {

    //uchovava url API
    static #apiUrl = '';

    //nastavnie zakladnej url pre API
    static setApiUrl(url) {
        if (!url?.trim?.())
            throw new Error('default api url must be provided');
        this.#apiUrl = url;
    }

    //GET na API
    static get(endpoint) {
        return this.#fetch(endpoint);
    }

    //POST na API
    static post(endpoint, data) {
        return this.#fetch(endpoint, data, "POST");
    }

    //OPTIONS na API
    static options(endpoint) {
        return this.#fetch(endpoint, null, "OPTIONS");
    }

    //PUT na API
    static put(endpoint, data) {
        return this.#fetch(endpoint, data, "PUT");
    }

    //DELETE na API
    static delete(endpoint) {
        return this.#fetch(endpoint, null, "DELETE");
    }

    //realizuje sa tu fetch
    static async #fetch(url, data = null, method = "GET") {
        //kontrola ci je url zadana
        if (url === undefined)
            throw new Error('fetch url must be provided.');
        //nastavenie hlavicky pre poziadavku s autorizaciou a crsf tokenom (bezpecne spracovanie poziadavky)
        const headers = {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token"),  // Pridáme token
            // Pridáme CSRF token
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        };

        //nastavenie moznosti
        const options = {
            method,   //GET,POST,PUT,DELETE,..
            body: JSON.stringify({ data: data || {} }), // Telo požiadavky, ak je potrebné. Posiela sa ako JSON.
            headers, //nastavene hlavicky
        };

        return await fetch(this.#apiUrl.replace(/\/$/, '') + '/' + url.replace(/^\//, ''), options)
            .then(response => response.json())
            .catch(error => console.error("Error:", error));  // Pri chybe
    }
}

