module.exports = {
    /**
     * A few helper methods for making HTTP requests and doing common form actions.
     */
    post: function (uri, form) {
        return App.sendForm('post', uri, form);
    },

    put: function (uri, form) {
        return App.sendForm('put', uri, form);
    },


    patch: function (uri, form) {
        return App.sendForm('patch', uri, form);
    },


    delete: function (uri, form) {
        return App.sendForm('delete', uri, form);
    },


    /**
     * Send the form to the back-end server. Perform common form tasks.
     *
     * This function will automatically clear old errors, update "busy" status, etc.
     */
    sendForm: function (method, uri, form) {
        return new Promise(function (resolve, reject) {
            form.startProcessing();

            Vue.http[method](uri, JSON.stringify(form))
                .then(function (response) {
                    form.finishProcessing();

                    resolve(JSON.parse(response.body));
                }, function (errors) {
                    error = JSON.parse(errors.body);
                    form.errors.set(error);
                    form.busy = false;

                    reject(error);
                });
        });
    }
};