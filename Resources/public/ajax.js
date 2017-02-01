var ajax = {
    get : function load(url, callback) {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = ensureReadiness;

            function ensureReadiness() {
                if(xhr.readyState < 4) {
                    return;
                }

                if(xhr.status !== 200) {
                    return;
                }

                // all is well
                if(xhr.readyState === 4) {
                    callback(xhr);
                }
            }

            xhr.open('GET', url, true);
            xhr.send('');
    },

    put : function load(url, payload, callback) {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = ensureReadiness;

            function ensureReadiness() {
                if(xhr.readyState < 4) {
                    return;
                }

                if(xhr.status !== 200) {
                    return;
                }

                // all is well
                if(xhr.readyState === 4) {
                    callback(xhr);
                }
            }

            xhr.open('PUT', url, true);
            xhr.send(payload);
    },

    delete : function load(url, payload, callback) {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = ensureReadiness;

            function ensureReadiness() {
                if(xhr.readyState < 4) {
                    return;
                }

                if(xhr.status !== 200) {
                    return;
                }

                // all is well
                if(xhr.readyState === 4) {
                    callback(xhr);
                }
            }

            xhr.open('DELETE', url, true);
            xhr.send(payload);
    },

    post: function load(url, payload, callback) {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = ensureReadiness;

            function ensureReadiness() {
                if(xhr.readyState < 4) {
                    return;
                }

                if(xhr.status !== 200) {
                    return;
                }

                // all is well
                if(xhr.readyState === 4) {
                    callback(xhr);
                }
            }

            xhr.open('POST', url, true);
            xhr.send(payload);
    }
}