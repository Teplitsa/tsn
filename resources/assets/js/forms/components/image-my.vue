<template>
    <div>
        <div :class="{'form-group': true, 'has-error': form.errors.has(name) }">
            <label class="col-sm-2 control-label">
                {{ display }}
                <br v-if="help != ''"/>
                <small v-if="help != ''" class="text-navy">{{ help }}</small>
            </label>
            <div class="col-sm-10 dropzone dz-clickable">
                <input type="file" style="display: none" name="scan" @change="onFileChange">

                <div v-if="form[name]">
                    <img :style="'height:200px; display: block; margin-bottom: 10px;'"
                         :src="form[name]"/>
                    <button class="btn btn-success" @click.prevent="selectImage()">Заменить изображение</button>

                </div>
                <div v-else>
                    <button class="btn btn-success" @click.prevent="selectImage()">Выбрать изображение</button>
                </div>

                <span class="help-block" v-show="form.errors.has(name)">
                    <strong>{{ form.errors.get(name) }}</strong>
                </span>


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
                var input = event.target;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = function (e) {
                        vm.form.scan = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            },
            createImage(file) {
                var reader = new FileReader();
                var vm = this;

                reader.onload = (e) => {
                    vm.form[vm.name] = e.target.result;
                },
                    reader.readAsDataURL(file);
            },
            selectImage()
            {
                $(this.$el).find('[name="scan"]').click();
            }
        }
    }
</script>
