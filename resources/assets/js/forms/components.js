import Text from './components/text.vue';
import Email from './components/email.vue';
import Password from './components/password.vue';
import Select from './components/select.vue';
import EnumSelect from './components/enum-select.vue';
import SelectMultiple from './components/select-multiple.vue';
import Image from './components/image.vue';
import ImageMy from './components/image-my.vue';
import Contact from './components/contact.vue';
import Input from './components/input.vue';
import TextHidden from './components/text-hidden.vue';
//import Marked from './components/marked.vue';

/**
 * Text field input component for Bootstrap.
 */
Vue.component('app-text', Text); 


/**
 * E-mail field input component for Bootstrap.
 */
Vue.component('app-email', Email);


/**
 * Password field input component for Bootstrap.
 */
Vue.component('app-password', Password);


/**
 * Select input component for Bootstrap.
 */
Vue.component('app-select', Select);


/**
 * Image.
 */
Vue.component('app-image', Image);


/**
 * Contact.
 */
Vue.component('app-contact', Contact);
Vue.component('app-enum-select', EnumSelect);
Vue.component('app-select-multiple', SelectMultiple);
Vue.component('app-inpit', Input);
Vue.component('app-text-hidden', TextHidden);
Vue.component('app-image-my', ImageMy);


//Vue.component('app-markdown-editor', Marked);