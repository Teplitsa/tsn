<template>
    <div>
        <div :class="{'form-group': true, 'has-error': form.errors.has(name) }">
            <label class="col-sm-2 control-label">
                {{ display }}
                <br v-if="help != ''"/>
                <small v-if="help != ''" class="text-navy">{{ help }}</small>
            </label>
            <div class="col-sm-10 dropzone dz-clickable">
                <span class="help-block" v-show="form.errors.has(name)">
                    <strong>{{ form.errors.get(name) }}</strong>
                </span>

                <div v-if="form[name]">
                    <img :style="'height: 200px; display: block; margin-bottom: 10px;'"
                         :src="form[name]"/>


                    <button class="btn btn-success" @click.prevent="selectImage()">Заменить изображение</button>

                </div>

                <button class="btn btn-success" @click.prevent="selectImage()" v-else>Выбрать изображение</button>

            </div>


        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default{
        props: ['display', 'form', 'name', 'help', 'email', 'width'],
        data: function () {
            return {
                avatar: []
            };
        },
        computed: {
            image_url: function () {
                if (this.form[this.name] == null || this.form[this.name] == '') {
                    return '/stub-avatar/' + this.email;
                }
                else {
                    return this.form[this.name];
                }
            }
        },
        methods: {
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                var reader = new FileReader();
                var vm = this;

                reader.onload = (e) => {
                    vm.form[vm.name] = e.target.result;
                },
                    reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                var vm = this;
                Vue.http.get('/internal-api/getavatar/' + this.email).then(function (response) {
                    vm.form[vm.name] = response.json().data.gravatar;
                }, function (response) {

                });
            },

            selectImage()
            {
                $(this.$el).find('input').click();
            }
        }
    }
</script>
