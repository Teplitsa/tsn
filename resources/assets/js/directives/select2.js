Vue.directive('select', {
    twoWay: true,
    bind: function () {
        $(this.el).select2()
            .on("select2:select", function(e) {
                this.set($(this.el).val());
            }.bind(this));
    },
    update: function (nv, ov) {
        $(this.el).trigger("change");
    }
});