window.Helpers.Ajax = {
    updateCurrentUser(success, fail)
    {
        app.http.get('/internal-api/user');
    }
};