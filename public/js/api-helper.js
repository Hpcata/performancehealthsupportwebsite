// API Helper Functions
const ApiHelper = {
    // Base API URL
    baseUrl: '/api',

    // Default headers
    defaultHeaders: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    },

    // Make API call
    async call(endpoint, method = 'GET', data = null, customHeaders = {}) {
        try {
            const response = await $.ajax({
                url: this.baseUrl + endpoint,
                method: method,
                data: data ? JSON.stringify(data) : null,
                headers: {
                    ...this.defaultHeaders,
                    ...customHeaders
                },
                xhrFields: {
                    withCredentials: false
                }
            });
            return response;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    // GET request
    async get(endpoint, params = {}, customHeaders = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.call(url, 'GET', null, customHeaders);
    },

    // POST request
    async post(endpoint, data = {}, customHeaders = {}) {
        return this.call(endpoint, 'POST', data, customHeaders);
    },

    // PUT request
    async put(endpoint, data = {}, customHeaders = {}) {
        return this.call(endpoint, 'PUT', data, customHeaders);
    },

    // DELETE request
    async delete(endpoint, customHeaders = {}) {
        return this.call(endpoint, 'DELETE', null, customHeaders);
    }
}; 